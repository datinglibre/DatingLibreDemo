Feature:
    As a user I can choose to be notified when I receive a new match

    @message
    Scenario: I want to notified when I receive a new match
        Given the following profiles exist:
            | email                 | attributes     | requirements   | city    | age |
            | bristol_1@example.com | blue, square   | yellow, circle | Bristol | 30  |
            | bath_1@example.com    | yellow, circle | blue, square   | Bath    | 30  |
        And the user "bristol_1@example.com" has a subscription
        When the user "bristol_1@example.com" sends the message "Hello" to "bath_1@example.com"
        Then  "bath_1@example.com" should have a new message with "Hello" from "bristol_1@example.com"
        And the user "bath_1@example.com" should have a new match email

    @message
    Scenario: I don't want to notified when I receive a new match
        Given the following profiles exist:
            | email                 | attributes     | requirements   | city    | age |
            | bristol_1@example.com | blue, square   | yellow, circle | Bristol | 30  |
            | bath_1@example.com    | yellow, circle | blue, square   | Bath    | 30  |
        And the user "bristol_1@example.com" has a subscription
        And the user "bath_1@example.com" has set "newMatchNotifications" to "false"
        When the user "bristol_1@example.com" sends the message "Hello" to "bath_1@example.com"
        Then  "bath_1@example.com" should have a new message with "Hello" from "bristol_1@example.com"
        And the user "bath_1@example.com" should not have a new match email