Feature: Adding Numbers by Events
    Scenario: Adding
        Given the following messages in the adding queue:
            | message       |
            | {"value": 5}  |
            | {"value": 2}  |
            | {"value": 7}  |
            | {"value": 9}  |
        When the messages in the queue are handled
        Then the total in the adding service should be "23"