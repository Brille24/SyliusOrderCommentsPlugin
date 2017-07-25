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

    @domain
    Scenario: Customer commented an order
        When a customer comments an order "#00000022" with "Hello"
        Then this order should have comment with "Hello" from this customer

    @todo
    Scenario: Sending the email notification to the administrator about unread customer's comments
        Given a customer commented the order "#00000022" with "Hello"
        Then the notification email should be sent to the administrator about "Hello" comment
