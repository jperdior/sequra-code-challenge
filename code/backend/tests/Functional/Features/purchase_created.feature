Feature: Purchase created

  Scenario: Small PurchaseCreatedEvent received
    Given I receive a "small" purchase event with body:
      """
        {"merchantReference":"aa1", "amount": 40, "createdAt": "2021-01-01T00:00:00Z"}
      """
    Then A disbursement with merchantReference "aa1" to disburse on "2021-01-01" containing fee 0.4 and amount 39.6 should be created
    And A disbursement line with purchaseId "small-purchase" with fee 0.4, amount 39.6 and fee percentage 100 should be created


    Scenario: Medium PurchaseCreatedEvent received
      Given I receive a "medium" purchase event with body:
          """
          {"merchantReference":"aa2", "amount": 100, "createdAt": "2021-01-01T00:00:00Z"}
          """
      Then A disbursement with merchantReference "aa2" to disburse on "2021-01-01" containing fee 0.95 and amount 99.05 should be created
      And A disbursement line with purchaseId "medium-purchase" with fee 0.95, amount 99.05 and fee percentage 95 should be created

Scenario: Large PurchaseCreatedEvent received
    Given I receive a "large" purchase event with body:
        """
        {"merchantReference":"aa3", "amount": 1000, "createdAt": "2021-01-01T00:00:00Z"}
        """
    Then A disbursement with merchantReference "aa3" to disburse on "2021-01-01" containing fee 8.5 and amount 991.5 should be created
    And A disbursement line with purchaseId "large-purchase" with fee 8.5, amount 991.5 and fee percentage 85 should be created