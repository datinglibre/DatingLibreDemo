Feature:
    I can report another user

    @report
    Scenario: I can report another user
        Given the following profiles exist:
            | email                          | attributes | requirements | city   | age |
            | chelsea_blue@example.com       | woman      | man          | London | 30  |
            | westminster_yellow@example.com | man        | woman        | London | 30  |
        And I am logged in with "chelsea_blue@example.com"
        And I am on "/search"
        Then I should see "westminster_yellow"
        And I follow "westminster_yellow"
        And I follow "Report"
        Then I should see "westminster_yellow"
        And I should see "30"
        And I should see "London"
        And I should see "M"
        And I check "Spam"
        And I fill in "report_form_message" with "Extra context"
        And I press "Report"
        Then I should see "Reported user"
        And a report should exist for "westminster_yellow@example.com" from "chelsea_blue@example.com"

    @report
    Scenario: I can report another user without a message
        Given the following profiles exist:
            | email                          | attributes | requirements | city   | age |
            | chelsea_blue@example.com       | woman      | man          | London | 30  |
            | westminster_yellow@example.com | man        | woman        | London | 30  |
        And I am logged in with "chelsea_blue@example.com"
        And I am on "/search"
        Then I should see "westminster_yellow"
        And I follow "westminster_yellow"
        And I follow "Report"
        And I check "Spam"
        And I press "Report"
        Then I should see "Reported user"
        And a report should exist for "westminster_yellow@example.com" from "chelsea_blue@example.com"

    @report
    Scenario: I cannot report the same user twice
        Given the following profiles exist:
            | email                          | attributes | requirements | city   | age |
            | chelsea_blue@example.com       | woman      | man          | London | 30  |
            | westminster_yellow@example.com | man        | woman        | London | 30  |
        And I am logged in with "chelsea_blue@example.com"
        And I am on "/search"
        Then I should see "westminster_yellow"
        And I follow "westminster_yellow"
        And I follow "Report"
        And I check "Spam"
        And I fill in "report_form_message" with "Extra context"
        And I press "Report"
        Then I should see "Reported user"
        And I follow "Report"
        Then I should see "You have reported this user"