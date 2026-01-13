# TODO List for Admin Menu and Subscription Management

## Original Task: Fix MenuController.php
- [x] Rename class from 'Menus' to 'MenuController' for consistency
- [x] Add validation to store method for menu_name and weekday
- [x] Add error handling to toggleStatus method

## Menu Form Add/Update Task
- [x] Add an update method to MenuController.php for editing existing menus.
- [x] Add a route in Routes.php for the update action (POST admin/menus/(:num)/update).
- [x] Modify the menus/index.php view to include an Edit button in the Actions column.
- [x] Add an Edit Menu modal (similar to Add, but pre-filled with existing data).
- [x] Update JavaScript to handle edit button click and populate the modal.
- [x] Test adding and updating menus to ensure it works.
  - Navigate to /admin/menus (using XAMPP server)
  - Add a new menu using the "Add Menu" button.
  - Edit an existing menu using the "Edit" button.
  - Verify that changes are saved and reflected in the list.

## New Responsibilities: Admin-side Controls and Backend Validations
- [ ] Review existing Admin Menu and Subscription controllers and models
- [ ] Implement or improve weekday-based menu assignment
- [ ] Handle active and inactive menu states
- [ ] Add validations to restrict menu mapping strictly to the correct weekday
- [ ] Ensure backend logic allows changes only for active subscriptions
- [ ] Limit menu replacement to the same weekday
- [ ] Ensure updates to database queries or models remain backward-compatible with existing data
- [ ] Achieve clean admin workflow, strong backend validations, and zero possibility of menu cross-mapping across weekdays or subscription plans

## Specific Tasks
- [ ] Analyze SubscriptionController.php changeDeliveryMenu method
- [ ] Update MenuModel to include weekday-based queries
- [ ] Modify SubscriptionDeliveryModel to enforce weekday constraints
- [ ] Add validation in SubscriptionController for menu changes
- [ ] Ensure only active menus are selectable
- [ ] Prevent cross-weekday menu assignments
- [ ] Test backward compatibility with existing data
