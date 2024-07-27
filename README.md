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
1. Clone project `git clone git@github.com:akmaks/test-text-magic.git && cd test-text-magic`
2. Init local environment `make setup`
3. Init githook for checking code before commit (local php is required) `make add-check-githook`
4. Start test `make run`

## How to test
Run unit tests `make test`

## Makefile
Start `make` to get help info about available commands
