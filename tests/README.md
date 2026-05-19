Controller inclusion and action simulation test harness

Run the controller tests locally to detect fatal errors, missing model methods, and simulated key controller actions.

Usage:

From the project root run:

php tests/run_controllers.php

Notes:
- This harness uses lightweight model mocks in `tests/mocks.php` to avoid missing class declarations.
- It first includes every controller to ensure files parse and load with model classes.
- It then runs controller-specific action scenarios from `tests/action_scenarios.php` using `tests/action_runner.php`.
- Scenarios simulate safe request flows for key controllers and verify that the controller code can execute without fatal errors.
- If you need deeper behavior validation, integrate PHPUnit and write targeted unit/integration tests.
