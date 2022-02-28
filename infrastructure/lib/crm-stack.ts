import * as cdk from '@aws-cdk/core';
import {RemovalPolicy} from '@aws-cdk/core';
import * as ec2 from '@aws-cdk/aws-ec2';
import * as rds from '@aws-cdk/aws-rds';
import * as ecs from '@aws-cdk/aws-ecs';
import * as iam from '@aws-cdk/aws-iam';
import * as ecr from '@aws-cdk/aws-ecr';
import * as acm from '@aws-cdk/aws-certificatemanager';
import * as route53 from '@aws-cdk/aws-route53';
import {ApplicationLoadBalancedFargateService} from '@aws-cdk/aws-ecs-patterns';
import {DockerImageAsset} from '@aws-cdk/aws-ecr-assets';
import * as wafv2 from '@aws-cdk/aws-wafv2';

import * as path from 'path';
import * as ecrdeploy from 'cdk-ecr-deployment';
import {IDestinyInputParams} from './utils';
import {SslPolicy} from '@aws-cdk/aws-elasticloadbalancingv2/lib/shared/enums';
import {
  FileSystem,
  LifecyclePolicy,
  OutOfInfrequentAccessPolicy,
  PerformanceMode,
  ThroughputMode
} from '@aws-cdk/aws-efs';

export class CrmStack extends cdk.Stack {
  constructor(params: IDestinyInputParams, scope: cdk.Construct, id: string, props?: cdk.StackProps) {
    super(scope, id, props);

    // Create IAM Users:
    // Application credentials
    // DevOps credentials
    const devOpsUser = new iam.User(this, params.devOpsUserName, {
      userName: params.devOpsUserName,
    });
    const devOpsUserKey = new iam.CfnAccessKey(this, params.devOpsUserKey, {
      userName: devOpsUser.userName,
    });

    const appUser = new iam.User(this, params.appUserName, {
      userName: params.appUserName,
    });
    const appUserKey = new iam.CfnAccessKey(this, params.appUserKey, {
      userName: appUser.userName,
    });

    const destinyDefaultDomainZone = route53.HostedZone.fromLookup(this, 'DestinyZone', { domainName: 'grupo-diana.dosmass.systems' });

    // Allow App user to send SES emails
    appUser.addToPolicy(new iam.PolicyStatement({
      resources: [`*`],
      actions: [
          'ses:SendEmail',
          'ses:SendRawEmail',
          'ses:GetAccount',
      ]
    }));

    if (params.createBackend) {
      this.createBackendComponents(
          params,
          appUserKey,
          devOpsUser,
          destinyDefaultDomainZone,
      );
    }

    /* Outputs */

    // DevOps Creds
    new cdk.CfnOutput(this, 'DevOps AccessKeyId', {
      value: devOpsUserKey.ref,
    });
    new cdk.CfnOutput(this, 'DevOps SecretAccessKeyId', {
      value: devOpsUserKey.attrSecretAccessKey,
    });
    // App Creds
    new cdk.CfnOutput(this, 'Application AccessKeyId', {
      value: appUserKey.ref,
    });
    new cdk.CfnOutput(this, 'Application SecretAccessKeyId', {
      value: appUserKey.attrSecretAccessKey,
    });
    // Original config
    new cdk.CfnOutput(this, 'Config', {
      value: JSON.stringify(params.originalConfig),
    });
  }

  private createBackendComponents(params: IDestinyInputParams,
                                  appUserKey: iam.CfnAccessKey,
                                  devOpsUser: iam.User,
                                  zone: route53.IHostedZone) {
    // VPC For RDS and ECS
    // TODO(jjescof): Parametrize me!
    const vpc = !params.useExistingVPC ?
        new ec2.Vpc(this, params.vpcName, { natGateways: 1 }) :
        ec2.Vpc.fromLookup(this, params.useExistingVPC, {vpcId: params.useExistingVPC});

    // We need this security group to allow our proxy to query our MySQL Instance
    let dbConnectionGroup = new ec2.SecurityGroup(this, params.secGroupName, {
      vpc
    });
    dbConnectionGroup.addIngressRule(dbConnectionGroup, ec2.Port.tcp(3306), 'Allow DB connection');

    // Serverless Aurora mysql
    // TODO(jjescof): Parametrize me!
    const scalingDBOpts = {
      autoPause: cdk.Duration.minutes(10),
      minCapacity: rds.AuroraCapacityUnit.ACU_1,
      maxCapacity: params.environment === 'production' ? rds.AuroraCapacityUnit.ACU_4 : rds.AuroraCapacityUnit.ACU_1,
    };
    const dbCluster = new rds.ServerlessCluster(this, params.rdsClusterName, {
      clusterIdentifier: params.rdsClusterName,
      engine: rds.DatabaseClusterEngine.AURORA_MYSQL,
      vpc,
      scaling: scalingDBOpts,
      defaultDatabaseName: params.rdsDatabaseName,
      removalPolicy: cdk.RemovalPolicy.DESTROY,
      backupRetention: cdk.Duration.days(10),
      securityGroups: [dbConnectionGroup],
    });

    // Docker repository
    const ecrRepo = new ecr.Repository(this, params.ecrRepositoryName, {
      repositoryName: params.ecrRepositoryName,
      removalPolicy: cdk.RemovalPolicy.DESTROY,
      imageScanOnPush: true,
    });
    ecrRepo.addLifecycleRule({
      rulePriority: 1,
      maxImageCount: 1,
      tagPrefixList: ['latest']
    });
    ecrRepo.addLifecycleRule({
      rulePriority: 2,
      maxImageAge: cdk.Duration.days(10)
    });
    ecrRepo.grantPullPush(devOpsUser);
    // A dummy image should be pushed in order to allow proper stack formation
    const image = new DockerImageAsset(this, 'CDKDockerImage', {
      directory: path.join(__dirname, '..', '..'),
    });
    new ecrdeploy.ECRDeployment(this, 'DeployDockerImage', {
      src: new ecrdeploy.DockerImageName(image.imageUri),
      dest: new ecrdeploy.DockerImageName(`${ecrRepo.repositoryUri}:latest`),
    });

    const loadBalancerCertificate = acm.Certificate.fromCertificateArn(this, 'LBDestinyCertificate', params.loadBalancerCertificateArn);

    // Backend Creation
    this.createLoadBalancerFargateService(
        params,
        vpc,
        dbCluster,
        ecrRepo,
        appUserKey,
        dbConnectionGroup,
        devOpsUser,
        loadBalancerCertificate,
        zone,
    );

    /* Outputs */
    // VPC ID
    new cdk.CfnOutput(this, 'VPC Id', {
      value: vpc.vpcId,
    });

    // Database secret ARN
    new cdk.CfnOutput(this, 'Database Secret ARN', {
      value: dbCluster.secret?.secretFullArn || 'Unknown',
    });

    // ECR
    new cdk.CfnOutput(this, 'ECR URI', {
      value: ecrRepo.repositoryUri,
    });
  }

  private createLoadBalancerFargateService(params: IDestinyInputParams,
                                           vpc: ec2.IVpc,
                                           dbCluster: rds.ServerlessCluster,
                                           ecrRepo: ecr.Repository,
                                           appUserKey: iam.CfnAccessKey,
                                           dbConnectionGroup: ec2.SecurityGroup,
                                           devOpsUser: iam.User,
                                           certificate: acm.ICertificate,
                                           zone: route53.IHostedZone) {
    if (!dbCluster.secret)
      throw new Error('Database secret is required and it is not generated');

    const lbDomainName = `${params.domain}`;
    const ecsFargateService = new ApplicationLoadBalancedFargateService(this, params.ecsClusterName, {
      vpc,
      loadBalancerName: params.applicationLoadBalancerName,
      certificate,
      domainZone: zone,
      domainName: lbDomainName,
      redirectHTTP: true,
      sslPolicy: SslPolicy.RECOMMENDED,
      // 1VCPU and 2GB === t2.small instance
      cpu: params.environment === 'production' ? 512 : 256 , // 1VCPU = 1024
      memoryLimitMiB: params.environment === 'production' ? 2048 : 1024, // 1024 = 1GB
      healthCheckGracePeriod: cdk.Duration.seconds(params.environment === 'production' ? 75 : 100),
      taskImageOptions: {
        image: ecs.ContainerImage.fromEcrRepository(ecrRepo, 'latest'),
        containerPort: 80,
        secrets: {
          MAUTIC_DB_HOST: ecs.Secret.fromSecretsManager(dbCluster.secret, 'host'),
          MAUTIC_DB_PORT: ecs.Secret.fromSecretsManager(dbCluster.secret, 'port'),
          MAUTIC_DB_NAME: ecs.Secret.fromSecretsManager(dbCluster.secret, 'dbname'),
          MAUTIC_DB_USER: ecs.Secret.fromSecretsManager(dbCluster.secret, 'username'),
          MAUTIC_DB_PASSWORD: ecs.Secret.fromSecretsManager(dbCluster.secret, 'password'),
        },
        environment: {
          MAUTIC_ENV: params.environment,
          AWS_ACCESS_KEY_ID: appUserKey.ref,
          AWS_SECRET_ACCESS_KEY: appUserKey.attrSecretAccessKey,
          AWS_DEFAULT_REGION: params.region,
          AWS_SENDER_NAME: params.senderName,
          AWS_SENDER_EMAIL: params.senderEmail,
          SITE_URL: params.mauticSiteURL,
          MAUTIC_SECRET_KEY: params.mauticSecret,
        },
      },
      propagateTags: ecs.PropagatedTagSource.SERVICE,
      securityGroups: [dbConnectionGroup],
      deploymentController: {
        type: ecs.DeploymentControllerType.ECS,
      },
      circuitBreaker: {
        rollback: true,
      } ,
      desiredCount: 1,
    });
    ecsFargateService.targetGroup.configureHealthCheck({
      path: '/mtc.js',
      port: '80',
      interval: cdk.Duration.seconds(70),
      timeout: cdk.Duration.seconds(30),
      healthyHttpCodes: '200,301,302'
    });
    ecsFargateService.targetGroup.setAttribute('deregistration_delay.timeout_seconds', '30');

    /* Add Execution property so we can emulate SSH */
    // Add need policy for EnabledExecuteCommand
    ecsFargateService.taskDefinition.taskRole.addManagedPolicy(iam.ManagedPolicy.fromAwsManagedPolicyName('AmazonSSMManagedInstanceCore'));
    // Use escape hatch to add EnabledExecuteCommand to cf template
    const cfnService = ecsFargateService.service.node.defaultChild as ecs.CfnService;
    cfnService.enableExecuteCommand = true;

    if (params.environment === 'production') {
      const scalableTarget = ecsFargateService.service.autoScaleTaskCount({
        minCapacity: 1,
        maxCapacity: 3,
      });
      scalableTarget.scaleOnCpuUtilization('Scale CPU Options', {
        targetUtilizationPercent: 80,
      });
      scalableTarget.scaleOnMemoryUtilization('Scale Memory Options', {
        targetUtilizationPercent: 80,
      });
    }

    // Set WebAcl to loadbalancer
    const acl = this.createWEBACL(params, 'REGIONAL');
    if (acl) {
      new wafv2.CfnWebACLAssociation(this, `${params.reducedBaseName} LB Association`,{
        resourceArn: ecsFargateService.loadBalancer.loadBalancerArn,
        webAclArn: acl.attrArn,
      });
    }

    devOpsUser.addToPolicy(new iam.PolicyStatement({
      resources: [ecsFargateService.service.serviceArn],
      actions: ['ecs:UpdateService']
    }));

    this.createEFSVolume(params, vpc, ecsFargateService, dbConnectionGroup);

    // Loadbalancer info
    new cdk.CfnOutput(this, 'Application Load Balancer Hostname', {
      value: ecsFargateService.loadBalancer.loadBalancerDnsName,
    });

    new cdk.CfnOutput(this, 'ECS Cluster Name', {
      value: ecsFargateService.cluster.clusterName,
    });

    new cdk.CfnOutput(this, 'ECS Service Name', {
      value: ecsFargateService.service.serviceName,
    });

    new cdk.CfnOutput(this, 'ECS Service ARN', {
      value: ecsFargateService.service.serviceArn,
    });
  }

  private createWEBACL(params: IDestinyInputParams, scope: string): wafv2.CfnWebACL | undefined {
    if (params.wafRules && Array.isArray(params.wafRules) && params.wafRules.length) {
      // Taken from https://gist.github.com/statik/f1ac9d6227d98d30c7a7cec0c83f4e64
      return new wafv2.CfnWebACL(this, `${params.stackName}-waf-${scope.toLowerCase()}`, {
        defaultAction: { allow: {} },
        visibilityConfig: {
          cloudWatchMetricsEnabled: true,
          metricName: `${params.stackName}-waf-metrics-${scope.toLowerCase()}`,
          sampledRequestsEnabled: false,
        },
        scope: scope, // 'REGIONAL' 'CLOUDFRONT'
        name: `${params.stackName}-waf-${scope.toLowerCase()}`,
        rules: params.wafRules.map(wafRule => wafRule.rule),
      });
    } else {
      return undefined;
    }
  }

  private createEFSVolume(params: IDestinyInputParams,
                          vpc: ec2.IVpc,
                          ecsFargateService: ApplicationLoadBalancedFargateService,
                          dbConnectionGroup: ec2.SecurityGroup) {
    const fileSystem = new FileSystem(this, `${params.stackName}-efs`, {
      vpc,
      lifecyclePolicy: LifecyclePolicy.AFTER_7_DAYS,
      performanceMode: PerformanceMode.GENERAL_PURPOSE,
      throughputMode: ThroughputMode.BURSTING,
      removalPolicy: RemovalPolicy.DESTROY,
      // outOfInfrequentAccessPolicy: OutOfInfrequentAccessPolicy.AFTER_1_ACCESS, // This should be enabled once library is fixed
      securityGroup: dbConnectionGroup
    });
    dbConnectionGroup.addIngressRule(dbConnectionGroup, ec2.Port.tcp(2049), 'Allow NFS connection');

    const volumeConfig = {
      name: `${params.stackName}-volume`,
      efsVolumeConfiguration: {
        fileSystemId: fileSystem.fileSystemId,
      },
    };

    ecsFargateService.taskDefinition.addVolume(volumeConfig);
    if (ecsFargateService.taskDefinition.defaultContainer) {
      ecsFargateService.taskDefinition.defaultContainer.addMountPoints({
        containerPath: "/var/www/html/media",
        sourceVolume: volumeConfig.name,
        readOnly: false,
      });
    }
    ecsFargateService.taskDefinition.taskRole.addToPrincipalPolicy(new iam.PolicyStatement({
      resources: [
          fileSystem.fileSystemArn
      ],
      actions: [
        'elasticfilesystem:ClientMount',
        'elasticfilesystem:ClientRootAccess',
        'elasticfilesystem:ClientWrite',
        'elasticfilesystem:DescribeMountTargets',
      ]
    }));
  }
}


// Dashboarding
// ECS: https://docs.aws.amazon.com/AmazonECS/latest/developerguide/cloudwatch-metrics.html
// Aurora: https://docs.aws.amazon.com/AmazonRDS/latest/AuroraUserGuide/Aurora.AuroraMySQL.Monitoring.Metrics.html
// S3: https://docs.aws.amazon.com/AmazonS3/latest/userguide/metrics-dimensions.html
//
