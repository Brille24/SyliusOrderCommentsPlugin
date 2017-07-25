@order_comments
Feature: Reading comments from a customer
    In order to check unread customer's comments
    As an Administrator
    I want to be able to communicate with my customers via order comments

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "PHP T-Shirt"
        And the store ships everywhere for free
        And the store allows paying with "Cash on Delivery"
        And there is a customer "john.doe@gmail.com" that placed an order "#00000022"
        And the customer bought a single "PHP T-Shirt"
        And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
        And I am logged in as an administrator

    @todo
    Scenario: Reading order's comments
        Given a customer commented the order "#00000022" with "Hello"
        Then this order should have comment with "Hello" from this customer
