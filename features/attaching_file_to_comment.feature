@customer_order_comments
Feature: Attaching file to comment
    In order to provide ability to attach file to comment
    As a Customer
    I want to be able to communicate with the store owner via order comments with more details

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
    Scenario: Attaching file to comment
        When I comment the order "#00000022" with "Hello" and "file.pdf" file
        Then this order should have a comment with "Hello" and file "file.pdf" from this customer
