#!/usr/bin/env node
import 'source-map-support/register';
import * as cdk from '@aws-cdk/core';
import { CrmStack } from '../lib/crm-stack';
import * as utils from '../lib/utils';

// PROJECT_NAME="JJTest1 CDK" BRAND_NAME=Destiny CLIENT_NAME=Nintera cdk deploy --profile JJ-APPS-WSUITE
// Will reuse this stack definition with a different name, allowing
// replicating the setup

const app = new cdk.App();
const params = utils.getParams();

new CrmStack(params, app, `CoreSystem/${params.stackName}`, {
  env: {
    // DK_DEFAULT_ACCOUNT and CDK_DEFAULT_REGION
    // These variables are set based on the AWS profile specified using the --profile option,
    // or the default AWS profile if you don't specify one.
    account: process.env.CDK_DEFAULT_ACCOUNT,
    region: process.env.CDK_DEFAULT_REGION,
  },
  stackName: params.stackName,
  tags: params.tags,
  description: `${params.clientName} - ${params.brandName} - ${params.projectName} - Mautic Stack`
});
