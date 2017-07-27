@order_comments
Feature: Commenting an order by a customer
    In order to provide ability to comment an order
    As a Customer
    I want to be able to communicate with the store owner via order comments

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "PHP T-Shirt"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And there was account of "john.doe@gmail.com" with password "sylius"
        And there is a customer "john.doe@gmail.com" that placed an order "#00000022"
        And the customer bought a single "PHP T-Shirt"
        And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
        And I am logged in as "john.doe@gmail.com"

    @domain @application
    Scenario: Customer commented an order
        When I comment an order "#00000022" with "Hello"
        Then this order should have a comment with "Hello" from this customer

    @domain @application
    Scenario: Customer cannot comment an order with an empty message
        When I try to comment an order "#00000022" with an empty message
        Then this order should not have empty comment from this customer

    @domain @application
    Scenario: Customer with invalid email cannot comment an order
        When a customer with email "notEmail" try to comment an order "#00000022"
        Then I should be notified that comment is invalid

    @application
    Scenario: Customer cannot comment an order which does not exist
        When I try to comment a not existing order with "Hello"
        Then I should be notified that comment is invalid

    @todo
    Scenario: Sending the email notification to the administrator about unread customer's comments
        Given a customer commented the order "#00000022" with "Hello"
        Then the notification email should be sent to the administrator about "Hello" comment
