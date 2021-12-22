import * as fs from 'fs';
import {slugify} from '@aws-cdk/aws-ec2/lib/util'
import { customAlphabet } from 'nanoid'
const nanoid = customAlphabet('1234567890abcdef', 64);

export interface IDestinyInputParams {
    stackName: string;
    region: string;
    vpcName: string;
    rdsClusterName: string;
    rdsDatabaseName: string; // DatabaseName must begin with a letter and contain only alphanumeric characters
    ecsClusterName: string;
    applicationLoadBalancerName: string;
    ecrRepositoryName: string;
    devOpsUserName: string;
    appUserName: string;
    devOpsUserKey: string;
    appUserKey: string;
    secGroupName: string;
    domain: string;
    environment: string;
    projectName: string;
    clientName: string;
    brandName: string;
    reducedBaseName: string;
    useExistingVPC: string | undefined;
    certificateArn: string;
    loadBalancerCertificateArn: string;
    createBackend: boolean;
    tags: {[key: string]: string};
    originalConfig: object;
    wafRules?: Array<any>;
    mauticSiteURL: string;
    mauticSecret: string;
    senderName: string;
    senderEmail: string;
};

function slugifyIt(value: string): string {
    return slugify(slugify(value)).toLowerCase();
}

export function getParams(): IDestinyInputParams {
    const environment = slugifyIt(process.env.ENVIRONMENT || 'production');
    const configFile = `${__dirname}/../config.${environment}.json`;
    const config = require(configFile);
    if (!config.mauticSecret) {
        config.mauticSecret = nanoid();
        let data = JSON.stringify(config, null, 2);
        fs.writeFileSync(configFile, data);
    }

    if (!config.projectName || !config.brandName || !config.clientName || !config.mauticSecret || !config.subDomain)
        throw new Error(`Config file ${configFile} missing variables required: projectName, brandName, clientName, mauticSecret, domainName`);

    const projectName = slugifyIt(config.projectName);
    const brandName = slugifyIt(config.brandName);
    const clientName = slugifyIt(config.clientName);
    const domain = config.subDomain;
    const mauticSiteURL = `http://${domain}.${config.domain ? config.domain : 'destiny.systems'}`;

    const baseServiceName = `mautic-${clientName}-${brandName}-${projectName}-${environment}`;
    const reducedBaseName = `mtc-${projectName}-${environment}`;
    const useExistingVPC = config.vpcId || undefined;
    const certificateArn = !!config.certificateArn ?
        config.certificateArn :
        'arn:aws:acm:us-east-1:657279016981:certificate/0a064616-4c08-4ca9-be6d-81a1fe240d60';
    const loadBalancerCertificateArn = !!config.loadBalancerCertificateArn ?
        config.loadBalancerCertificateArn :
        'arn:aws:acm:us-east-1:657279016981:certificate/0a064616-4c08-4ca9-be6d-81a1fe240d60';
    const createBackend = !!config.createBackend;
    const wafRules = config.wafRules || null;

    const vpcName = `${reducedBaseName}-vpc`;
    const rdsClusterName = `${reducedBaseName}-rds-serverless`;
    const rdsDatabaseName = `${reducedBaseName}-db`.replace(/[^a-zA-Z0-9]/g, '');
    const ecsClusterName = `${reducedBaseName}-ecs-cluster`;
    const albName = `${reducedBaseName}-alb`;
    const ecrRepositoryName = `${reducedBaseName}-ecr`;
    const devOpsUserName = `${reducedBaseName}-devops`;
    const appUserName = `${reducedBaseName}-app`;
    const devOpsUserKey = `${reducedBaseName}-devops-key`;
    const appUserKey = `${reducedBaseName}-app-key`;
    const secGroupName = `${reducedBaseName}-sec-group`;

    if (albName.length > 32) {
        throw new Error(`LoadBalancer name exceeds 32 character length, current ${albName.length}`);
    }

    return {
        stackName: baseServiceName,
        region: process.env.AWS_REGION || 'us-east-1',
        vpcName,
        rdsClusterName,
        rdsDatabaseName,
        ecsClusterName,
        applicationLoadBalancerName: albName,
        ecrRepositoryName,
        devOpsUserName,
        appUserName,
        devOpsUserKey,
        appUserKey,
        environment,
        secGroupName,
        projectName,
        clientName,
        brandName,
        reducedBaseName,
        domain,
        useExistingVPC,
        certificateArn,
        createBackend,
        loadBalancerCertificateArn,
        tags: {
            Billing: projectName,
            Brand: brandName,
            Client:	clientName,
            Environment: environment,
        },
        wafRules,
        originalConfig: config,
        mauticSecret: config.mauticSecret,
        mauticSiteURL,
        senderName: config.senderName || 'Info',
        senderEmail: config.senderEmail || 'info@destiny.systems',
    };
};
