@customer_order_comments
Feature: Commenting an order by a customer
    In order to provide ability to comment an order
    As a Customer
    I want to be able to communicate with the store owner via order comments

    Background:
        Given the store operates on a single channel in "United States"
        And this channel has "customer.service@test.com" as a contact email
        And the store has a product "PHP T-Shirt"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And there was account of "john.doe@gmail.com" with password "sylius"
        And there is a customer "john.doe@gmail.com" that placed an order "#00000022"
        And the customer bought a single "PHP T-Shirt"
        And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
        And I am logged in as "john.doe@gmail.com"

    @domain @application @ui
    Scenario: Customer commented an order
        When I comment the order "#00000022" with "Hello"
        Then this order should have a comment with "Hello" from this customer

    @application @ui
    Scenario: Customer commented an order
        Given a customer "john.doe@gmail.com" placed an order "#00000023"
        And the customer bought a single "PHP T-Shirt"
        And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
        When I comment the order "#00000023" with "Hello"
        Then this order should have a comment with "Hello" from this customer
        But the order "#00000022" should not have any comments

    @domain @application
    Scenario: Customer cannot comment an order with an empty message
        When I try to comment the order "#00000022" with an empty message
        Then I should be notified that comment is invalid
        And this order should not have any comments

    @domain @application
    Scenario: Customer with invalid email cannot comment an order
        When a customer with email "notEmail" try to comment an order "#00000022"
        Then I should be notified that comment is invalid
        And this order should not have any comments

    @application
    Scenario: Customer cannot comment an order which does not exist
        When I try to comment a not existing order with "Hello"
        Then I should be notified that comment is invalid
        And this order should not have any comments

    @application
    Scenario: Sending the email notification to the administrator about unread customer's comments
        Given I have commented the order "#00000022" with "Hello"
        Then the notification email should be sent to the administrator about "Hello" comment
