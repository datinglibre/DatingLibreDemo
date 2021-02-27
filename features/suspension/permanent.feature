Feature:
    As a moderator
    I can queue a user for permanent suspension

    @suspension
    Scenario: A moderator can enter a user into the permanent suspension queue.
        Given the following profiles exist:
            | email                 | attributes |
            | reporter@example.com  | man        |
            | suspended@example.com | woman      |
        And the user "reporter@example.com" has reported "suspended@example.com"
        And a moderator exists with email "moderator@example.com"
        And I am logged in with "moderator@example.com"
        And I am on "/moderator/reports"
        And I follow "suspended"
        And I follow "Permanently suspend"
        And I should see "Are you sure you want to suspend and enter this profile into the permanent suspension queue?"
        And I check "Abusive messages"
        And I press "Confirm"
        And the user "suspended@example.com" should receive a suspension email for "Abusive messages"
        Then I should see "Profile has been entered into queue for permanent suspension"
        And I should not see "Are you sure you want to suspend and enter this profile into the permanent suspension queue?"
        And I follow "profile-menu-suspensions"
        Then I should see "Permanent"
        Then I should see "Open"
        And I should see "Abusive messages"
        And I follow "Logout"
        And I am logged in with "suspended@example.com"
        Then I should see "The suspension will be reviewed."
        And I follow "Edit"
        Then I should see "The suspension will be reviewed."

    @suspension
    Scenario: An administrator can permanently suspend a profile
        Given the following profiles exist:
            | email                 |
            | reporter@example.com  |
            | suspended@example.com |
        And the user "reporter@example.com" has reported "suspended@example.com"
        And an administrator exists with email "admin@example.com"
        And I am logged in with "admin@example.com"
        And I follow "Moderate"
        And I follow "Reports"
        Then I follow "suspended"
        And I follow "Permanently suspend"
        And I check "Abusive messages"
        And I press "Confirm"
        Then I should see "Profile has been permanently suspended"
        And the user "suspended@example.com" should receive a permanent suspension email with "Abusive messages"
        And I follow "profile-menu-suspensions"
        Then I should see "Open"

    @suspension
    Scenario: A permanent suspension by an administrator overrides a previous suspension
        Given the following profiles exist:
            | email               |
            | newuser@example.com |
        And a moderator exists with email "moderator@example.com"
        And the moderator "moderator@example.com" has suspended "newuser@example.com" for "spam" for "72" hours
        And an administrator exists with email "admin@example.com"
        And the administrator "admin@example.com" has permanently suspended "newuser@example.com"
        Then the user "newuser@example.com" should receive a permanent suspension email with "Spam"

    @suspension
    Scenario: An administrator can confirm a permanent suspension
        Given the following profiles exist:
            | email               | attributes |
            | newuser@example.com | man        | 
        And a moderator exists with email "moderator@example.com"
        And the moderator "moderator@example.com" has entered "newuser@example.com" into the permanent suspension queue
        And an administrator exists with email "admin@example.com"
        And I am logged in with "admin@example.com"
        And I follow "Moderate"
        And I follow "Permanent suspensions"
        Then I should see "newuser"
        And I should see "Spam"
        And I should not see "Profile has been permanently suspended"
        And I follow "newuser"
        And I follow "Permanently suspend"
        And I check "Abusive messages"
        And I press "Confirm"
        Then I should see "Profile has been permanently suspended"
        And I follow "profile-menu-suspensions"
        Then I should see "Spam"
        Then I should see "Abusive messages"
        And I follow "Permanently suspend"
        Then I should see "Profile has been permanently suspended"
        And I follow "Logout"
        And I am logged in with "newuser@example.com"
        Then I should see "Suspended"

    @suspension
    Scenario: An administrator can close a permanent suspension
        Given the following profiles exist:
            | email               |
            | newuser@example.com |
        And a moderator exists with email "moderator@example.com"
        And the moderator "moderator@example.com" has entered "newuser@example.com" into the permanent suspension queue
        And an administrator exists with email "admin@example.com"
        And I am logged in with "admin@example.com"
        And I follow "Moderate"
        And I follow "Permanent suspensions"
        And I follow "newuser"
        And I follow "profile-menu-suspensions"
        And I follow "Close"
        And I press "Close"
        Then I should see "Closed"
        And I follow "Moderate"
        And I follow "Permanent suspensions"
        Then I should not see "newuser"

   @suspension
   Scenario: a permanent suspension results in a cancelled subscription
       Given the following profiles exist:
           | email               | attributes |
           | newuser@example.com | man        |
       And the user "newuser@example.com" has a "datinglibre" subscription with ID "985938"
       And an administrator exists with email "admin@example.com"
       And the administrator "admin@example.com" has permanently suspended "newuser@example.com"
       Then the subscription for "newuser@example.com" is cancelled
       And I log in using email "newuser@example.com"
       And I am on "/profile"
       Then I should see "your profile has been permanently suspended for the reasons below"
       And I should see "Your subscription, if it exists, has been cancelled."
       And I should see "Spam"
       And I am on "/account/subscription"
       And I should see "Cancelled"
       And I should see "Your profile has been permanently suspended for the reasons below"
       And I should see "Your subscription, if it exists, has been cancelled"
       Then I should not see "Buy a subscription"

   @suspension
   Scenario: A user with a permanent suspension cannot change their profile image
       Given the following profiles exist:
           | email               | attributes |
           | newuser@example.com | man        |
       And an administrator exists with email "admin@example.com"
       And the administrator "admin@example.com" has permanently suspended "newuser@example.com"
       And I am logged in with "newuser@example.com"
       And I am on "/profile/image"
       Then I should see "your profile has been permanently suspended for the reasons below"