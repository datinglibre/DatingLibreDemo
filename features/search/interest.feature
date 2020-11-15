Feature:
    As a user
    I want to find users that match my interests

    @search
    Scenario: I can successfully find a user when I filter by an interest that matches
        Given the following profiles exist:
            | email                    | attributes    | requirements  | city    | age | interests |
            | bristol_blue@example.com | man, switch   | woman, switch | Bristol | 30  | music     |
            | bath_yellow@example.com  | woman, switch | man, switch   | Bath    | 30  | fitness   |
        And the following filters exist:
            | email                    | distance | min_age | max_age |
            | bristol_blue@example.com | 100000   | 18      | 100     |
        And the following interest filters exist:
            | email                    | interests |
            | bristol_blue@example.com | fitness   |
        When the user "bristol_blue@example.com" searches for matches
        Then the user "bath_yellow@example.com" matches

    @search
    Scenario: I can filter a user when their interests don't match my filter
        Given the following profiles exist:
            | email                    | attributes    | requirements  | city    | age | interests |
            | bristol_blue@example.com | man, switch   | woman, switch | Bristol | 30  | music     |
            | bath_yellow@example.com  | woman, switch | man, switch   | Bath    | 30  | fitness   |
            | chelsea_blue@example.com | woman, switch | man, switch   | Bath    | 30  |           |
        And the following filters exist:
            | email                    | distance | min_age | max_age |
            | bristol_blue@example.com | 100000   | 18      | 100     |
        And the following interest filters exist:
            | email                    | interests |
            | bristol_blue@example.com | sports    |
        When the user "bristol_blue@example.com" searches for matches
        Then the user "bath_yellow@example.com" does not match
        And the user "chelsea_blue@example.com" does not match

    @search
    Scenario: I can view users where their interests match at least one of my filters
        Given the following profiles exist:
            | email                    | attributes    | requirements  | city    | age | interests |
            | bristol_blue@example.com | man, switch   | woman, switch | Bristol | 30  | music     |
            | bath_yellow@example.com  | woman, switch | man, switch   | Bath    | 30  | fitness   |
            | chelsea_blue@example.com | woman, switch | man, switch   | Bath    | 30  | sports    |
        And the following filters exist:
            | email                    | distance | min_age | max_age |
            | bristol_blue@example.com | 100000   | 18      | 100     |
        And the following interest filters exist:
            | email                    | interests       |
            | bristol_blue@example.com | sports, fitness |
        When the user "bristol_blue@example.com" searches for matches
        Then the user "bath_yellow@example.com" matches
        And the user "chelsea_blue@example.com" matches

    @search
    Scenario: I can match a user when their interests match both of my filters
        Given the following profiles exist:
            | email                    | attributes    | requirements  | city    | age | interests       |
            | bristol_blue@example.com | man, switch   | woman, switch | Bristol | 30  | music           |
            | bath_yellow@example.com  | woman, switch | man, switch   | Bath    | 30  | fitness         |
            | chelsea_blue@example.com | woman, switch | man, switch   | Bath    | 30  | fitness, sports |
        And the following filters exist:
            | email                    | distance | min_age | max_age |
            | bristol_blue@example.com | 100000   | 18      | 100     |
        And the following interest filters exist:
            | email                    | interests       |
            | bristol_blue@example.com | fitness, sports |
        When the user "bristol_blue@example.com" searches for matches
        Then the user "bath_yellow@example.com" matches
        And the user "chelsea_blue@example.com" matches