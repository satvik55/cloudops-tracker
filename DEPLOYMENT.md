# Deployment Guide — AWS EC2 (Ubuntu)

This document covers deploying CloudOps Incident Tracker on an AWS EC2 instance running Ubuntu 22.04.

## Prerequisites

- AWS account with EC2 access
- SSH key pair (`.pem` file)
- Basic terminal familiarity

## 1. Launch EC2 Instance

- **AMI:** Ubuntu Server 22.04 LTS
- **Instance type:** `t2.micro` (free tier) or `t3.small`
- **Security group inbound rules:**
  - Port `22` (SSH) — your IP only
  - Port `80` (HTTP) — `0.0.0.0/0`
- **Key pair:** download and save the `.pem` file

## 2. Connect via SSH

```bash
chmod 400 /path/to/privatekey.pem
ssh -i /path/to/privatekey.pem ubuntu@<EC2_PUBLIC_IP>
```

## 3. Install Docker on the VM

```bash
sudo apt update
sudo apt install -y ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg \
  -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

sudo tee /etc/apt/sources.list.d/docker.sources <<DOCKER_EOF
Types: deb
URIs: https://download.docker.com/linux/ubuntu
Suites: $(. /etc/os-release && echo "${UBUNTU_CODENAME:-$VERSION_CODENAME}")
Components: stable
Architectures: $(dpkg --print-architecture)
Signed-By: /etc/apt/keyrings/docker.asc
DOCKER_EOF

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io \
  docker-buildx-plugin docker-compose-plugin

sudo usermod -aG docker ubuntu
exit
```

Reconnect so the group change takes effect.

## 4. Transfer Project to VM

From your local machine:

```bash
scp -i /path/to/privatekey.pem -r ./cloudops-tracker \
  ubuntu@<EC2_PUBLIC_IP>:/home/ubuntu/
```

## 5. Build and Run

```bash
ssh -i /path/to/privatekey.pem ubuntu@<EC2_PUBLIC_IP>
cd /home/ubuntu/cloudops-tracker
docker compose build
docker compose up -d
docker compose ps
```

Both services should show `Up`. MySQL reports healthy within ~30s.

## 6. Access

Open `http://<EC2_PUBLIC_IP>` in your browser.

## Seed Demo Data (optional)

```bash
docker compose exec mysql mysql -u root -prootpassword cloudops_tracker -e "
INSERT INTO incidents (title, service, severity, status, assigned_to, description) VALUES
('API Gateway returning 503 errors', 'API', 'P1', 'Investigating', 'sre-oncall', 'Users reporting 503 on /v1/orders endpoint'),
('Database replication lag > 10s', 'Database', 'P2', 'Open', 'db-team', 'Read replica in ap-south-1 lagging behind primary'),
('CSS not loading on Safari', 'Frontend', 'P3', 'Resolved', 'web-team', 'Cache purge resolved the issue'),
('Kubernetes pod OOMKilled in prod', 'Kubernetes', 'P2', 'Investigating', 'platform-team', 'checkout-service pod restarted 4 times in last hour'),
('Expired TLS cert on staging', 'Network', 'P4', 'Resolved', 'devops', 'Renewed via cert-manager'),
('S3 bucket 403 errors from Lambda', 'Storage', 'P2', 'Open', 'cloud-team', 'IAM policy drift, investigating'),
('Jenkins build queue stuck', 'CI/CD', 'P3', 'Resolved', 'devops', 'Restarted agent pool');"
```

## Troubleshooting

**Page shows "Database is still starting up":** Wait 20–30 seconds and refresh. MySQL is still bootstrapping.

**Port 80 already in use:** `sudo lsof -i :80` to find what's using it.

**Reset everything (including DB data):**
```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

**View logs:**
```bash
docker compose logs -f app
docker compose logs -f mysql
```
