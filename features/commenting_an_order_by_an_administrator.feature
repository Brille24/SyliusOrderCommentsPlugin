@order_comments
Feature: Commenting an order by an administrator
    In order to respond to the customers comment
    As an Administrator
    I want to be able to add a comment to the order

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "PHP T-Shirt"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And there was account of "john.doe@gmail.com" with password "sylius"
        And there is a customer "john.doe@gmail.com" that placed an order "#00000022"
        And the customer bought a single "PHP T-Shirt"
        And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
        And I am logged in as an administrator

    @todo
    Scenario: Administrator commented an order
        When I comment an order "#00000022" with "How can I help you?"
        Then this order should have a comment with "How can I help you?" from this customer

    @todo
    Scenario: Administrator cannot comment an order with an empty message
        When I try to comment an order "#00000022" with an empty message
        Then this order should not have any comment

    @todo
    Scenario: Administrator cannot comment an order which does not exist
        When I try to comment a not existing order with "How can I help you?"
        Then this order should not have empty comment from this customer

    @todo
    Scenario: Sending the email notification to the customer about unread administrator's comments
        Given I have commented the order "#00000022" with "How can I help you?"
        Then the notification email should be sent to the administrator
