#!/bin/bash

# ================================================
# Invoke Royalty Chaincode Transaction Script
# ================================================
# Usage:
#   ./invoke_royalty.sh CreateRoyalty txID contentID userID amount txHash paymentMethod createdAt
#   ./invoke_royalty.sh GetRoyaltyByID txID
# ================================================

set -e

# ————————————————————————————
# Configuration
# ————————————————————————————
export PATH=${PWD}/../bin:$PATH
export FABRIC_CFG_PATH=${PWD}/../config

ORDERER_ADDR="localhost:7050"
ORDERER_HOST="orderer.example.com"
CHANNEL_NAME="mychannel"
CC_NAME="royalty"

# Org1 (RoyaltyOrg) settings
ORG1_MSP="Org1MSP"
ORG1_PEER_ADDR="localhost:7051"
ORG1_TLS_ROOTCERT_FILE=${PWD}/organizations/peerOrganizations/org1.example.com/peers/peer0.org1.example.com/tls/ca.crt
ORG1_MSPCONFIGPATH=${PWD}/organizations/peerOrganizations/org1.example.com/users/Admin@org1.example.com/msp

# Org2 (AuditOrg) settings
ORG2_MSP="Org2MSP"
ORG2_PEER_ADDR="localhost:9051"
ORG2_TLS_ROOTCERT_FILE=${PWD}/organizations/peerOrganizations/org2.example.com/peers/peer0.org2.example.com/tls/ca.crt
ORG2_MSPCONFIGPATH=${PWD}/organizations/peerOrganizations/org2.example.com/users/Admin@org2.example.com/msp

# Orderer TLS cert
ORDERER_CA=${PWD}/organizations/ordererOrganizations/example.com/orderers/${ORDERER_HOST}/msp/tlscacerts/tlsca.example.com-cert.pem

# ————————————————————————————
# Environment helpers
# ————————————————————————————
setEnvOrg1() {
  export CORE_PEER_LOCALMSPID="$ORG1_MSP"
  export CORE_PEER_TLS_ROOTCERT_FILE="$ORG1_TLS_ROOTCERT_FILE"
  export CORE_PEER_MSPCONFIGPATH="$ORG1_MSPCONFIGPATH"
  export CORE_PEER_ADDRESS="$ORG1_PEER_ADDR"
  export CORE_PEER_TLS_ENABLED=true
}

setEnvOrg2() {
  export CORE_PEER_LOCALMSPID="$ORG2_MSP"
  export CORE_PEER_TLS_ROOTCERT_FILE="$ORG2_TLS_ROOTCERT_FILE"
  export CORE_PEER_MSPCONFIGPATH="$ORG2_MSPCONFIGPATH"
  export CORE_PEER_ADDRESS="$ORG2_PEER_ADDR"
  export CORE_PEER_TLS_ENABLED=true
}

# ————————————————————————————
# Parse arguments
# ————————————————————————————
if [ $# -lt 2 ]; then
  echo "Usage:"
  echo "  $0 CreateRoyalty txID contentID userID amount txHash paymentMethod createdAt"
  echo "  $0 GetRoyaltyByID txID"
  exit 1
fi

FUNCTION=$1
shift

case "$FUNCTION" in
  CreateRoyalty)
    if [ $# -ne 7 ]; then
      echo "Error: CreateRoyalty requires 7 args"
      exit 1
    fi
    TX_ID=$1;       CONTENT_ID=$2; USER_ID=$3
    AMOUNT=$4;      TX_HASH=$5;    PAYMENT_METHOD=$6
    CREATED_AT=$7
    JSON_ARGS=$(printf \
      '{"Args":["CreateRoyalty","%s","%s","%s","%s","%s","%s","%s"]}' \
      "$TX_ID" "$CONTENT_ID" "$USER_ID" \
      "$AMOUNT" "$TX_HASH" "$PAYMENT_METHOD" "$CREATED_AT")
    ;;
  GetRoyaltyByID)
    if [ $# -ne 1 ]; then
      echo "Error: GetRoyaltyByID requires 1 arg"
      exit 1
    fi
    TX_ID=$1
    JSON_ARGS=$(printf '{"Args":["GetRoyaltyByID","%s"]}' "$TX_ID")
    ;;
  *)
    echo "Unknown function: $FUNCTION"
    exit 1
    ;;
esac

# ————————————————————————————
# Invoke transaction
# ————————————————————————————
echo "Invoking $FUNCTION on channel '$CHANNEL_NAME' chaincode '$CC_NAME'..."
echo "Payload: $JSON_ARGS"

# Use Org1's identity to submit the invoke (endorsement from both peers)
setEnvOrg1
peer chaincode invoke \
  -o $ORDERER_ADDR --ordererTLSHostnameOverride $ORDERER_HOST \
  --tls --cafile "$ORDERER_CA" \
  -C $CHANNEL_NAME -n $CC_NAME \
  --peerAddresses $ORG1_PEER_ADDR --tlsRootCertFiles "$ORG1_TLS_ROOTCERT_FILE" \
  --peerAddresses $ORG2_PEER_ADDR --tlsRootCertFiles "$ORG2_TLS_ROOTCERT_FILE" \
  -c "$JSON_ARGS"

echo "Invoke transaction submitted."
