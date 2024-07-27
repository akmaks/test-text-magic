# Test-text-magic

## General structure of the service:
```text
src
├── DataFixtures  # App fixtures for testing
├── Entity        # Domain logic layer
├── Repository    # Repository layer
├── Service       # Service layer
└── UI            # UI layer (console, web, api,...)
    ├── Console
    └── Web
```

## Getting started
1. Clone project `git clone git@github.com:akmaks/test-text-magic.git`
2. Init local environment `make setup`
3. Init githook for checking code before pushing (local php is required) `make add-check-githook`
4. Start test `make run`

## How to test
Run unit tests `make tests`

## Makefile
Start `make` to get help info about available commands
