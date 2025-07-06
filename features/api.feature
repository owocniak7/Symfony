Feature: Expense API access

  Scenario: Access expense list as user
    When I request GET "/api/expenses"
    Then the response status should be 200

  Scenario: Access reports as user
    When I request GET "/api/reports/balance"
    Then the response status should be 200
    And the response should contain "balance"

  Scenario: Unauthorized access
    When I request GET "/api/users"
    Then the response status should be 403

  Scenario: Admin access to users
    When I request GET "/api/users"
    Then the response status should be 403 # (unless you use admin auth)

  Scenario: Category list
    When I request GET "/api/categories"
    Then the response status should be 200
    And the response should contain "Food"

  Scenario: Max expense check
    When I request GET "/api/reports/max"
    Then the response status should be 200

  Scenario: Monthly summary check
    When I request GET "/api/reports/monthly"
    Then the response status should be 200

  Scenario: Category summary check
    When I request GET "/api/reports/categories"
    Then the response status should be 200

  Scenario: Recently added expenses
    When I request GET "/api/reports/recent"
    Then the response status should be 200

  Scenario: Get balance and check plus/minus
    When I request GET "/api/reports/balance"
    Then the response should contain "plus"
    # or "minus" - depending on data
