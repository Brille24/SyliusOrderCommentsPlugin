@administrator_order_comments
Feature: Sorting administrator comments
    In order to track the conversation properly
    As an Administrator
    I want to be see comments from the oldest to the newest

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

    @ui
    Scenario: Administrator commented an order
        Given I have commented the order "#00000022" with "Hello"
        And I have commented the order "#00000022" with "How can I help you?"
        Then the first comment from the top should have the "Hello" message
