# LAMP Stack on AWS ECS with Copilot CLI

A simple PHP LAMP application deployed to Amazon ECS using AWS Copilot CLI, demonstrating containerized web application deployment with MySQL database integration.

## Repository & Live Demo

- **GitHub Repository:** [github repo](https://github.com/mr-robertamoah/lamp-ecs-app)
- **Live Application:** [live link](http://lampst-Publi-gFS0KVMxLve8-364471546.eu-west-1.elb.amazonaws.com)
*Note: This link may become unavailable after deployment teardown*

## Project Overview

This project showcases a basic LAMP (Linux, Apache, MySQL, PHP) stack application deployed to AWS ECS using AWS Copilot CLI. The application is a simple task management system that demonstrates CRUD operations with a MySQL database.

## Application Architecture

- **Frontend:** PHP with Bootstrap CSS
- **Backend:** PHP with MySQLi
- **Database:** MySQL 5.7
- **Container Platform:** Amazon ECS (Fargate)
- **Load Balancer:** Application Load Balancer
- **Deployment Tool:** AWS Copilot CLI

## Solution Architecture

**Path:** `solutions architecture/copilot ecs lamp stack.png`
![Solution Architecture](solutions%20architecture/copilot%20ecs%20lamp%20stack.png)
*Complete AWS solution architecture showing the LAMP stack deployment on ECS using Copilot CLI, including VPC, subnets, load balancer, ECS services, and EFS storage for MySQL persistence.*

## Deployment Journey & Challenges

### Initial Setup
1. **Local Development:** Started with `docker-compose.yml` for local testing
2. **Containerization:** Created `Dockerfile` with PHP 7.4-Apache and MySQLi extension and `Dockerfile.mysql` with mysql:5.7 
3. **Copilot Initialization:** Used `copilot app init` and `copilot svc init` to scaffold the deployment

### Database Connection Challenges

#### Challenge 1: No Such File or Directory
- **Issue:** `mysqli::__construct(): (HY000/2002): No such file or directory`
- **Root Cause:** PHP attempted Unix socket connection with `localhost`
- **Solution:** Changed hostname to `127.0.0.1` (sidecar container name)

#### Challenge 2: Connection Refused (127.0.0.1)
- **Issue:** PHP tried connecting to `127.0.0.1:3306` but MySQL wasn't available
- **Root Cause:** Could not find root cause
- **Solution:** Implemented environment variables and updated connection logic and finally switched to the use of `mysql-service`

### Deployment Evolution

1. **Sidecar Approach (Initial Attempt):**
   - MySQL runs as sidecar container alongside PHP
   - Simple configuration but limited scalability
   - Both containers share the same task definition

2. **Separate Services Architecture (Final Solution):**
   - MySQL deployed as separate Backend Service
   - PHP application as Load Balanced Web Service
   - Independent scaling and resource management
   - Service Connect for inter-service communication

3. **Environment Variables Integration:**
   - Moved from hardcoded values to environment-based configuration
   - Added `.env` file support for local development
   - Configured Copilot manifest with proper variable injection

## File Structure

```
lamp-ecs-app/
├── app/
│   ├── index.php          # Main PHP application
│   ├── .env              # Environment variables
│   └── .env.example      # Environment template
├── copilot/
│   ├── environments/
│   │   └── production/
│   │       ├── manifest.yml    # Environment config
│   ├── lampstack-app-service/
│   │   └── manifest.yml  # Service configuration
│   └── mysql-service/
│       └── manifest.yml  # Backend MySQL service
├── Dockerfile            # Container definition
├── Dockerfile.msql       # Container definition
├── docker-compose.yml    # Local development
└── README.md
```

## Screenshots

### AWS CLI Profile Configuration
**Path:** `screenshots/1 showing list of configured aws cli profiles.png`
![AWS CLI Profiles](screenshots/1%20showing%20list%20of%20configured%20aws%20cli%20profiles.png)
*Shows the configured AWS CLI profiles used for deployment.*

### ECS CLI Configuration
**Path:** `screenshots/2 configuring ecs cli after installation.png`
![ECS CLI Configuration](screenshots/2%20configuring%20ecs%20cli%20after%20installation.png)
*Configuring ECS CLI after installation.*

### ECS Features Deprecation Notice
**Path:** `screenshots/3 most features of ecs being deprecated.png`
![ECS Deprecation](screenshots/3%20most%20features%20of%20ecs%20being%20deprecated.png)
*Notice showing most ECS CLI features being deprecated.*

### ECS CLI Repository Recommendation
**Path:** `screenshots/4 ecs cli repo recommending use of copilot cli.png`
![ECS CLI Recommendation](screenshots/4%20ecs%20cli%20repo%20recommending%20use%20of%20copilot%20cli.png)
*ECS CLI repository recommending the use of Copilot CLI.*

### Copilot CLI Installation
**Path:** `screenshots/5 installed aws copilot cli.png`
![Copilot CLI Installation](screenshots/5%20installed%20aws%20copilot%20cli.png)
*Terminal output showing successful installation of AWS Copilot CLI.*

### Environment Variables Setup
**Path:** `screenshots/6 set the credentials environment variables.png`
![Environment Variables](screenshots/6%20set%20the%20credentials%20environment%20variables.png)
*Setting up AWS credentials environment variables.*

### Application Initialization
**Path:** `screenshots/7 initialize and set the name of the application.png`
![App Initialization](screenshots/7%20initialize%20and%20set%20the%20name%20of%20the%20application.png)
*Copilot CLI initializing the lampstack-app application.*

### Load Balancer Configuration
**Path:** `screenshots/8 attach a public facing load balancer.png`
![Load Balancer Setup](screenshots/8%20attach%20a%20public%20facing%20load%20balancer.png)
*Configuring the public-facing load balancer for the web service.*

### Dockerfile Location Setup
**Path:** `screenshots/9 set location of dockerfile after namin app service.png`
![Dockerfile Location](screenshots/9%20set%20location%20of%20dockerfile%20after%20namin%20app%20service.png)
*Setting the location of Dockerfile after naming the app service.*

### Environment Deployment Agreement
**Path:** `screenshots/10 agreeing to deploy environment for app service.png`
![Environment Deployment Agreement](screenshots/10%20agreeing%20to%20deploy%20environment%20for%20app%20service.png)
*Agreeing to deploy environment for the app service.*

### Environment Name Setup
**Path:** `screenshots/11 setting name of the environment.png`
![Environment Name](screenshots/11%20setting%20name%20of%20the%20environment.png)
*Setting the name of the deployment environment.*

### Infrastructure Creation
**Path:** `screenshots/12 creating resources underlying the ecs infrastructure.png`
![Infrastructure Creation](screenshots/12%20creating%20resources%20underlying%20the%20ecs%20infrastructure.png)
*AWS resources being created for the ECS infrastructure including VPC, subnets, and security groups.*

### ECR Image Push
**Path:** `screenshots/13 app built and pushed to ecr.png`
![ECR Push](screenshots/13%20app%20built%20and%20pushed%20to%20ecr.png)
*Application Docker image being built and pushed to Amazon ECR.*

### Initial Deployment Error
**Path:** `screenshots/14 app accessible but with error.png`
![Initial Error](screenshots/14%20app%20accessible%20but%20with%20error.png)
*Application accessible via load balancer but showing database connection error.*

### Dockerfile Update and Redeployment
**Path:** `screenshots/15 updated dockerfile and redeployed.png`
![Dockerfile Update](screenshots/15%20updated%20dockerfile%20and%20redeployed.png)
*Updated Dockerfile and redeployed the application.*

### MySQL Backend Service Deployment
**Path:** `screenshots/16 initiated and deployed a mysql backend service.png`
![MySQL Service](screenshots/16%20initiated%20and%20deployed%20a%20mysql%20backend%20service.png)
*Initiating and deploying MySQL as a separate backend service.*

### MySQL Service Port Configuration
**Path:** `screenshots/17 redeploying mysql service after properly exposing port 3306.png`
![MySQL Port Config](screenshots/17%20redeploying%20mysql%20service%20after%20properly%20exposing%20port%203306.png)
*Redeploying MySQL service after properly exposing port 3306.*

### Service Discovery Configuration
**Path:** `screenshots/18 showed the mysql service name and copilot service discovery endpoint.png`
![Service Discovery](screenshots/18%20showed%20the%20mysql%20service%20name%20and%20copilot%20service%20discovery%20endpoint.png)
*Showing the MySQL service name and Copilot service discovery endpoint configuration.*

### App Service Redeployment
**Path:** `screenshots/19 redeploying app service after updating host env value with mysql service endpoint.png`
![App Service Redeploy](screenshots/19%20redeploying%20app%20service%20after%20updating%20host%20env%20value%20with%20mysql%20service%20endpoint.png)
*Redeploying app service after updating host environment value with MySQL service endpoint.*

### Successful Application
**Path:** `screenshots/20 application runs successfully.png`
![Success](screenshots/20%20application%20runs%20successfully.png)
*Application running successfully with database connectivity established.*

### ECS Cluster View
**Path:** `screenshots/21 showing ecs cluster.png`
![ECS Cluster](screenshots/21%20showing%20ecs%20cluster.png)
*AWS ECS console showing the deployed cluster with running services.*

### Service List
**Path:** `screenshots/22 list services.png`
![Service List](screenshots/22%20list%20services.png)
*Listing all deployed services in the ECS cluster.*

### Service Logs
**Path:** `screenshots/23 showing some logs of a service.png`
![Service Logs](screenshots/23%20showing%20some%20logs%20of%20a%20service.png)
*CloudWatch logs showing service execution and debugging information.*

### ECR Repositories
**Path:** `screenshots/24 showing ecr repositories.png`
![ECR Repositories](screenshots/24%20showing%20ecr%20repositories.png)
*Amazon ECR repositories containing the deployed application images.*

### MySQL Service EFS Volume Setup
**Path:** `screenshots/25 reploying mysql service after setting it up to use efs volume.png`
![MySQL EFS Setup](screenshots/25%20reploying%20mysql%20service%20after%20setting%20it%20up%20to%20use%20efs%20volume.png)
*Redeploying MySQL service after configuring EFS volume for data persistence.*

### Testing Data Persistence
**Path:** `screenshots/26 testing persistence of data in mysql service.png`
![Testing Persistence](screenshots/26%20testing%20persistence%20of%20data%20in%20mysql%20service.png)
*Testing MySQL data persistence with EFS volume to ensure data survives container restarts.*

### Application After MySQL EFS Update
**Path:** `screenshots/27 viewing application after update to mysql service.png`
![App After MySQL Update](screenshots/27%20viewing%20application%20after%20update%20to%20mysql%20service.png)
*Viewing the application after updating MySQL service with EFS volume configuration.*

### Redeploying with Styling Updates
**Path:** `screenshots/28 redeploying lamstack service after making changes to styling.png`
![Styling Update Deploy](screenshots/28%20redeploying%20lamstack%20service%20after%20making%20changes%20to%20styling.png)
*Redeploying the lampstack service after making UI styling improvements.*

### Final Styled Application
**Path:** `screenshots/29 viewing application after redeploying lampstack service.png`
![Final Styled App](screenshots/29%20viewing%20application%20after%20redeploying%20lampstack%20service.png)
*Final application with improved styling, showing the modern UI with gradient backgrounds and enhanced user experience.*

## Key Configuration Files

### Dockerfile
```dockerfile
FROM php:7.4-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY ./app/ /var/www/html/
EXPOSE 80
```

### Copilot Service Manifests (Final)

**Web Service (lampstack-app-service):**
```yaml
name: lampstack-app-service
type: Load Balanced Web Service
image:
  build: Dockerfile
  port: 80
network:
  connect: true
variables:
  DB_HOST: mysql-service.production.lampstack-app.local
  DB_PORT: 3306
  DB_USER: root
  DB_PASSWORD: rootpassword
  DB_NAME: lampdb
```

**Backend Service (mysql-service):**
```yaml
name: mysql-service
type: Backend Service
image:
  location: mysql:5.7
port: 3306
variables:
  MYSQL_ROOT_PASSWORD: rootpassword
  MYSQL_DATABASE: lampdb
network:
  connect: true
storage:
  volumes:
    mysql-data:
      efs: true
      path: /var/lib/mysql
      read_only: false
mount_points:
  - source_volume: mysql-data
    container_path: /var/lib/mysql
```

## Deployment Commands

```bash
# Initialize application
copilot app init lampstack-app

# Initialize services
copilot svc init lampstack-app-service
copilot svc init mysql-service

# Deploy mysql service with EFS persistence
copilot svc deploy --name mysql-service --env production

# Deploy app service
copilot svc deploy --name lampstack-app-service --env production
```

## Possible Improvements

### 1. Database Persistence with EFS ✅ IMPLEMENTED
- **Current State:** MySQL data persists across container restarts using EFS volume
- **Implementation:** EFS volume mounted to `/var/lib/mysql` in MySQL container
- **Benefits:** Data persistence, backup capabilities, shared storage

### 2. Amazon RDS Integration
- **Current State:** MySQL runs as separate Backend Service
- **Improvement:** Replace with Amazon RDS MySQL instance
- **Benefits:** 
  - Managed service with automatic backups
  - High availability with Multi-AZ deployment
  - Automatic scaling and maintenance
  - Better security with VPC isolation
- **Implementation:** Use RDS addon in Copilot environment

### 3. Security Enhancements
- **Secrets Management:** Use AWS Systems Manager Parameter Store for database credentials
- **SSL/TLS:** Enable HTTPS with ACM certificates

### 4. Monitoring & Observability
- **CloudWatch Integration:** Enhanced logging and metrics (some level of logging is done automatically by ECS)
- **Application Performance Monitoring:** AWS X-Ray integration
- **Health Checks:** Custom health check endpoints

### 5. CI/CD Pipeline
- **GitHub Actions:** Automated deployment pipeline
- **Multi-environment:** Staging and production environments
- **Blue-Green Deployment:** Zero-downtime deployments

## What I Learned

### AWS Copilot CLI
- **Service Types:** Understanding Load Balanced Web Service vs Backend Service
- **Sidecar Patterns:** When and how to use sidecar containers effectively
- **Service Discovery:** How ECS services communicate within the same environment
- **Infrastructure as Code:** Declarative infrastructure management with manifests

### Container Networking
- **Service Discovery:** Different approaches to container-to-container communication
- **Hostname Resolution:** Understanding how container names resolve to network addresses
- **Port Mapping:** Proper configuration of container ports and service discovery

### Database Connectivity Patterns
- **Connection Strings:** Impact of hostname choice on connection methods (TCP vs Unix socket)
- **Environment Variables:** Best practices for configuration management
- **Container Orchestration:** Database deployment patterns in containerized environments

### AWS ECS Fundamentals
- **Task Definitions:** How containers are grouped and deployed
- **Service Management:** Auto-scaling, health checks, and deployment strategies
- **Load Balancing:** Integration with Application Load Balancer
- **VPC Networking:** Container networking within AWS VPC

### Debugging Containerized Applications
- **Log Analysis:** Using CloudWatch logs for troubleshooting
- **Connection Issues:** Systematic approach to network connectivity problems
- **Configuration Management:** Environment-specific configuration strategies

### DevOps Best Practices
- **Infrastructure as Code:** Version-controlled infrastructure definitions
- **Environment Parity:** Maintaining consistency between local and cloud environments
- **Deployment Automation:** Streamlined deployment processes with CLI tools

This project provided hands-on experience with modern containerized application deployment, highlighting the importance of proper service discovery, network configuration, and the trade-offs between different architectural patterns in cloud-native applications.