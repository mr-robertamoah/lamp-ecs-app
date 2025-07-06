#!/bin/bash

# === CONFIGURATION ===
APP_URL="http://lampst-Publi-gFS0KVMxLve8-364471546.eu-west-1.elb.amazonaws.com"         # Replace with your PHP app URL (e.g. from `copilot svc show`)
CLUSTER_NAME="lampstack-app-production-Cluster-ZY8OBWbcOK1Z"   # Replace with your ECS cluster name
SERVICE_NAME="mysql-service"              # MySQL service name in ECS
REGION="eu-west-1"                        # AWS region

TASK_TITLE="persistent-task-$(date +%s)"  # Unique title for test
TMP_FILE=$(mktemp)

# === 1. Add a Task ===
echo "[+] Adding task \"$TASK_TITLE\""
curl -s -X POST "$APP_URL" \
  -d "title=$TASK_TITLE" \
  -d "add=Add+Task" > /dev/null

# === 2. Check that it was created ===
echo "[+] Verifying task exists..."
curl -s "$APP_URL" > "$TMP_FILE"
if grep -q "$TASK_TITLE" "$TMP_FILE"; then
  echo "[✔] Task found before restart."
else
  echo "[✘] Task not found before restart. Exiting."
  exit 1
fi

# === 3. Restart MySQL Task ===
echo "[+] Restarting MySQL task..."
TASK_ARN=$(aws ecs list-tasks \
  --cluster "$CLUSTER_NAME" \
  --service-name "$SERVICE_NAME" \
  --region "$REGION" \
  --query 'taskArns[0]' \
  --output text)

if [[ "$TASK_ARN" == "None" ]]; then
  echo "[✘] No running task found for service. Exiting."
  exit 1
fi

aws ecs stop-task \
  --cluster "$CLUSTER_NAME" \
  --task "$TASK_ARN" \
  --region "$REGION" \
  > /dev/null

echo "[...] Waiting for service to stabilize (45s)..."
sleep 45  # Wait for ECS to relaunch a new task

# === 4. Check Task Again ===
echo "[+] Verifying task after restart..."
curl -s "$APP_URL" > "$TMP_FILE"
if grep -q "$TASK_TITLE" "$TMP_FILE"; then
  echo "[✔] Task still exists after restart. ✅ Persistence is working."
else
  echo "[✘] Task missing after restart. ❌ Persistence failed."
fi

# === Cleanup ===
rm "$TMP_FILE"
