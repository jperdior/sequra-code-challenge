Feature: Purchase created event received

  Background:
    Given there's these merchants in the database:
      | reference         | liveOn        | disbursementFrequency | minimumMonthlyFee |
      | aa1               | 2021-01-01    | DAILY                 | 0.0               |
      | aa2               | 2021-01-01    | DAILY                 | 0.0               |
      | aa3               | 2021-01-01    | DAILY                 | 0.0               |
      | bb                | 2021-01-01    | WEEKLY                | 100.0             |

  Scenario: Small purchase created event received
    When I receive a "small" purchase event with body:
      """
        {"merchantReference":"aa1", "amount": 40, "createdAt": "2021-01-01T00:00:00Z"}
      """
    Then A disbursement with merchantReference "aa1" to disburse on "2021-01-01" containing fee 0.4 and amount 39.6 should be created
    And A disbursement line with purchaseId "small-purchase" with fee 0.4, amount 39.6 and fee percentage 100 should be created


  Scenario: Medium PurchaseCreatedEvent received
    When I receive a "medium" purchase event with body:
        """
        {"merchantReference":"aa2", "amount": 100, "createdAt": "2021-01-01T00:00:00Z"}
        """
    Then A disbursement with merchantReference "aa2" to disburse on "2021-01-01" containing fee 0.95 and amount 99.05 should be created
    And A disbursement line with purchaseId "medium-purchase" with fee 0.95, amount 99.05 and fee percentage 95 should be created

  Scenario: Large PurchaseCreatedEvent received
    When I receive a "large" purchase event with body:
      """
      {"merchantReference":"aa3", "amount": 1000, "createdAt": "2021-01-01T00:00:00Z"}
      """
    Then A disbursement with merchantReference "aa3" to disburse on "2021-01-01" containing fee 8.5 and amount 991.5 should be created
    And A disbursement line with purchaseId "large-purchase" with fee 8.5, amount 991.5 and fee percentage 85 should be created

  Scenario: Several created purchases events received that should be in the same disbursement
    # Several purchases in one disbursement
    When I receive these purchase created events:
      | purchaseId | merchantReference  | amount | createdAt             |
      | small      | aa1                | 40    | 2021-01-02T00:00:00Z  |
      | medium     | aa1                | 100    | 2021-01-02T00:00:00Z  |
      | large      | aa1                | 1000    | 2021-01-02T00:00:00Z  |
    Then A disbursement with merchantReference "aa1" to disburse on "2021-01-02" containing fee 9.85 and amount 1130.15 should be created
    And A disbursement line with purchaseId "small" with fee 0.4, amount 39.6 and fee percentage 100 should be created
    And A disbursement line with purchaseId "medium" with fee 0.95, amount 99.05 and fee percentage 95 should be created
    And A disbursement line with purchaseId "large" with fee 8.5, amount 991.5 and fee percentage 85 should be created

  Scenario: First disbursement of month with minimum monthly fee:
    Given there's a merchant monthly fee in the database with reference "bb", date "2020-01-12" and fee amount 5
    When I receive a purchase event with body:
      """
      {"merchantReference":"bb", "amount": 40, "createdAt": "2021-01-01T00:00:00Z"}
      """
    Then A disbursement with merchantReference "bb" to disburse on "2021-01-01" with fee 0.4, amount 39.6, monthly fee 95 and flagged as first of month true should be created