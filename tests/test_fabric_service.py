import sys
import os
import json
from datetime import datetime

# Add the blockchain directory to the Python path
sys.path.append(os.path.join(os.path.dirname(__file__), '..', 'blockchain'))
from fabric_service import FabricService

class FabricServiceTest:
    def __init__(self):
        self.service = FabricService()

    def test_record_transaction(self):
        print("Testing transaction recording...")
        
        transaction_data = {
            'transaction_id': f'test-tx-{datetime.now().timestamp()}',
            'content_id': 'test-content-123',
            'user_id': 'test-user-456',
            'amount': 100.00,
            'timestamp': datetime.now().isoformat()
        }

        result = self.service.record_royalty_transaction(transaction_data)
        
        if result['success']:
            print("✓ Transaction recorded successfully")
            print(f"  Transaction ID: {result['transaction_id']}")
            print(f"  Block Number: {result['block_number']}")
            return True
        else:
            print(f"✗ Failed to record transaction: {result['error']}")
            return False

    def test_get_transaction_history(self):
        print("\nTesting transaction history retrieval...")
        
        user_id = 'test-user-456'
        result = self.service.get_transaction_history(user_id)
        
        if result['success']:
            transactions = result['transactions']
            print(f"✓ Retrieved {len(transactions)} transactions")
            for tx in transactions:
                print(f"  Transaction: {tx['transaction_id']}")
                print(f"  Amount: {tx['amount']}")
            return True
        else:
            print(f"✗ Failed to get transaction history: {result['error']}")
            return False

    def test_get_transaction_details(self):
        print("\nTesting transaction details retrieval...")
        
        # First record a transaction
        transaction_data = {
            'transaction_id': f'test-tx-{datetime.now().timestamp()}',
            'content_id': 'test-content-789',
            'user_id': 'test-user-101',
            'amount': 200.00,
            'timestamp': datetime.now().isoformat()
        }
        
        record_result = self.service.record_royalty_transaction(transaction_data)
        
        if not record_result['success']:
            print(f"✗ Failed to record test transaction: {record_result['error']}")
            return False
            
        # Now get the details
        result = self.service.get_transaction_details(record_result['transaction_id'])
        
        if result['success']:
            transaction = result['transaction']
            print("✓ Transaction details retrieved successfully")
            print(f"  Transaction ID: {transaction['transaction_id']}")
            print(f"  Amount: {transaction['amount']}")
            print(f"  Status: {transaction['status']}")
            return True
        else:
            print(f"✗ Failed to get transaction details: {result['error']}")
            return False

if __name__ == "__main__":
    print("=== Fabric Service Tests ===")
    test = FabricServiceTest()
    test.test_record_transaction()
    test.test_get_transaction_history()
    test.test_get_transaction_details() 