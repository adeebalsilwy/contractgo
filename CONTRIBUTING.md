# Contributing to Contract Management System

Thank you for considering contributing to the Contract Management System! Your efforts help make this project better for everyone.

## Code of Conduct

By participating in this project, you agree to abide by our [Code of Conduct](CODE_OF_CONDUCT.md).

## How Can I Contribute?

### Reporting Bugs
- Ensure the bug was not already reported by searching existing issues
- Use a clear and descriptive title
- Provide as much detail as possible about the environment and steps to reproduce
- Include screenshots if applicable

### Suggesting Features
- Explain the feature clearly and its intended use case
- Describe the benefits and potential impact
- Consider the complexity and feasibility

### Submitting Pull Requests
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for new functionality
5. Run existing tests (`php artisan test`)
6. Commit your changes with a clear message
7. Push to the branch
8. Open a Pull Request

## Development Setup

1. Clone your fork of the repository
2. Install dependencies: `composer install` and `npm install`
3. Set up your environment: copy `.env.example` to `.env` and configure
4. Run migrations: `php artisan migrate`
5. Generate application key: `php artisan key:generate`

## Coding Standards

- Follow PSR-12 coding standards
- Write clear, readable code with appropriate comments
- Maintain consistency with existing codebase
- Use meaningful variable and function names
- Write comprehensive tests for new features

## Pull Request Guidelines

- Keep PRs focused on a single issue/feature
- Update documentation if applicable
- Include tests for new functionality
- Ensure all tests pass before submitting
- Use descriptive commit messages
- Reference related issues in your PR description

## Style Guide

### PHP
- Follow PSR-12 standards
- Use 4 spaces for indentation
- Keep lines under 120 characters when possible
- Document complex functions with PHPDoc

### JavaScript
- Use consistent indentation (4 spaces)
- Prefer const/let over var
- Use descriptive variable names
- Follow Airbnb JavaScript Style Guide conventions

### CSS
- Use consistent naming conventions
- Organize styles logically
- Comment complex CSS selectors

## Testing

- Write tests for new features
- Ensure existing tests continue to pass
- Aim for high test coverage
- Test edge cases and error conditions

## Getting Help

If you need help:
- Check the existing documentation
- Open an issue with the "question" label
- Reach out to maintainers

Thank you for contributing!