# Publicacao em Producao no Azure com Docker Compose e GitHub

Este guia mostra um exemplo completo para publicar o JobFunnel em um novo recurso Azure usando VM Linux, Docker Compose e deploy automatizado pelo GitHub.

## 1) Arquitetura do exemplo

- Azure Resource Group
- Azure Virtual Machine (Ubuntu 22.04 LTS)
- Docker Engine + Docker Compose na VM
- Projeto versionado no GitHub
- GitHub Actions para deploy via SSH

## 2) Criar recursos no Azure (Azure CLI)

Autentique:

```bash
az login
```

Crie Resource Group:

```bash
az group create --name rg-jobfunnel-prod --location brazilsouth
```

Crie VM Ubuntu:

```bash
az vm create \
  --resource-group rg-jobfunnel-prod \
  --name vm-jobfunnel-prod \
  --image Ubuntu2204 \
  --admin-username azureuser \
  --generate-ssh-keys \
  --size Standard_B2s
```

Abra portas (ajuste conforme sua estrategia de proxy/tls):

```bash
az vm open-port --resource-group rg-jobfunnel-prod --name vm-jobfunnel-prod --port 22
az vm open-port --resource-group rg-jobfunnel-prod --name vm-jobfunnel-prod --port 80
az vm open-port --resource-group rg-jobfunnel-prod --name vm-jobfunnel-prod --port 443
az vm open-port --resource-group rg-jobfunnel-prod --name vm-jobfunnel-prod --port 8080
```

## 3) Preparar VM para Docker

Acesse a VM:

```bash
ssh azureuser@<IP_PUBLICO_DA_VM>
```

Instale Docker:

```bash
sudo apt-get update
sudo apt-get install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo $VERSION_CODENAME) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo usermod -aG docker $USER
newgrp docker
```

## 4) Publicar o codigo na VM

```bash
mkdir -p ~/apps
cd ~/apps
git clone https://github.com/diforg/jobfunnel.git
cd jobfunnel
```

Crie `.env` de producao (na VM):

```bash
cp .env.example .env
```

Ajuste no `.env`:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://seu-dominio.com` (ou http://IP:8080 no inicio)
- Senhas fortes de banco e credenciais necessarias

## 5) Subir stack de producao

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
```

Execute migracoes:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T php php artisan migrate --force
```

(Opcional) seed inicial:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml exec -T php php artisan db:seed --force
```

## 6) Integracao com GitHub Actions (deploy automatico)

Crie os secrets no repositorio GitHub:
- `AZURE_VM_HOST` (IP ou dominio)
- `AZURE_VM_USER` (ex.: `azureuser`)
- `AZURE_VM_SSH_KEY` (chave privada SSH)
- `AZURE_VM_APP_DIR` (ex.: `/home/azureuser/apps/jobfunnel`)

Workflow de deploy: [/.github/workflows/deploy-azure-vm.yml](../.github/workflows/deploy-azure-vm.yml)

Funcionamento:
- Ao fazer push na branch `main`, o workflow conecta por SSH na VM
- Atualiza o codigo (`git fetch/reset`)
- Recria a stack de producao com Compose
- Roda migrations com `--force`

## 7) Verificacoes pos deploy

Na VM:

```bash
cd ~/apps/jobfunnel
docker compose -f docker-compose.yml -f docker-compose.prod.yml ps
docker compose -f docker-compose.yml -f docker-compose.prod.yml logs -f --tail=100
```

Aplicacao:
- `http://<ip-da-vm>:8080` (sem proxy TLS)
- recomendado: apontar dominio + reverse proxy TLS em 443

## 8) Boas praticas recomendadas

- Nao expor 5432/6379 publicamente em producao
- Guardar segredos somente no Azure Key Vault ou GitHub Secrets
- Configurar backup de volume PostgreSQL
- Configurar monitoramento (Azure Monitor / logs centralizados)
- Configurar pipeline de rollback
