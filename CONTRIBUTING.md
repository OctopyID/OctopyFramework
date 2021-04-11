# Contributing to OctopycFramework

## Contributions

We expect all contributions to conform to our style guide, be commented (inside the PHP source files), be documented (in the user guide), and unit tested (in the test folder).
There is a [Contributing to OctopyFramework](./contributing/README.rst) section in the repository which describes the contribution process; this page is an overview.

## Issues

Issues are a quick way to point out a bug. If you find a bug or documentation error in OctopyFramework then please check a few things first:

1. There is not already an open Issue
2. The issue has already been fixed (check the develop branch, or look for closed Issues)
3. Is it something really obvious that you can fix yourself?

Reporting issues is helpful but an even [better approach](./contributing/workflow.rst) is to send a Pull Request, which is done by "Forking" the main repository and committing to
your own copy. This will require you to use the version control system called Git.

## Guidelines

Before we look into how, here are the guidelines. If your Pull Requests fail to pass these guidelines it will be declined and you will need to re-submit when you’ve made the
changes. This might sound a bit tough, but it is required for us to maintain quality of the code-base.

### PHP Style

All code must meet the [Style Guide](./contributing/styleguide.rst). This makes certain that all code is the same format as the existing code and means it will be as readable as
possible.

### Documentation

If you change anything that requires a change to documentation then you will need to add it. New classes, methods, parameters, changing default values, etc are all things that will
require a change to documentation. The change-log must also be updated for every change. Also PHPDoc blocks must be maintained.

### Compatibility

OctopyFramework requires PHP 7.3

### Branching

OctopyFramework uses the [Git-Flow](http://nvie.com/posts/a-successful-git-branching-model/) branching model which requires all pull requests to be sent to the "develop" branch.
This is where the next planned version will be developed. The "master" branch will always contain the latest stable version and is kept clean so a "hotfix" (e.g: an emergency
security patch) can be applied to master to create a new version, without worrying about other features holding it up. For this reason all commits need to be made to "develop" and
any sent to "master" will be closed automatically. If you have multiple changes to submit, please place all changes into their own branch on your fork.

One thing at a time: A pull request should only contain one change. That does not mean only one commit, but one change - however many commits it took. The reason for this is that
if you change X and Y but send a pull request for both at the same time, we might really want X but disagree with Y, meaning we cannot merge the request. Using the Git-Flow
branching model you can create new branches for both of these features and send two requests.

## How-to Guide

The best way to contribute is to fork the OctopyFramework repository, and "clone" that to your development area. That sounds like some jargon, but "forking" on GitHub means "making
a copy of that repo to your account" and "cloning" means "copying that code to your environment so you can work on it".

1. Set up Git (Windows, Mac & Linux)
2. Go to the OctopyFramework repo
3. Fork it (to your Github account)
4. Clone your OctopyFramework repo: git@github.com:\<your-name>/OctopyFramework.git
5. Create a new branch in your project for each set of changes you want to make.
6. Fix existing bugs on the Issue tracker after taking a look to see nobody else is working on them.
7. Commit the changed files in your contribution branch
8. Push your contribution branch to your fork
9. Send a pull request [http://help.github.com/send-pull-requests/](http://help.github.com/send-pull-requests/)

The codebase maintainers will now be alerted about the change and at least one of the team will respond. If your change fails to meet the guidelines it will be bounced, or feedback
will be provided to help you improve it.

Once the maintainer handling your pull request is happy with it they will merge it into develop and your patch will be part of the next release.
