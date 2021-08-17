---
layout: page
title: Adding extra plugins
---

Sometimes the plugin that you are testing may depend on another plugin or even several other plugins.  This project
provides a way to Git clone the extra plugins and add them to the Moodle test site. Here is an example of how to use
it in your `.github/workflow/*` or `.travis.yml` files:

```yaml
  - moodle-plugin-ci add-plugin moodlehq/moodle-local_hub
  - moodle-plugin-ci install
```

You may add as many plugins as you like, by simply calling the `add-plugin` command for each plugin.  The `add-plugin`
command takes a single argument and that is your GitHub account name and the project name.  So, in the example, it
would clone `https://github.com/moodlehq/moodle-local_hub.git`.

By default, the branch that is cloned is the `master` branch.  You can use the `--branch` (`-b`) option to override
this behavior.  If you use the same branch names as Moodle (EG: `MOODLE_XY_STABLE`), then a handy trick is to pass
the `$MOODLE_BRANCH` build variable to the `add-plugin` command.  Here is an example:

```yaml
install:
  - moodle-plugin-ci add-plugin --branch $MOODLE_BRANCH username/project
  - moodle-plugin-ci install
```

If you need to add a plugin contained within a private repo, you can use a [Github encrypted secret](https://docs.github.com/en/actions/reference/encrypted-secrets).
In the example below, the secret is named 'REPO_TOKEN'. The value of the secret should be a [Personal access token](https://docs.github.com/en/github/authenticating-to-github/keeping-your-account-and-data-secure/creating-a-personal-access-token)
that has access to the repository you are adding.
NOTE - This is a security risk, as the PAT will be sent to moodle-plugin-ci. The PAT could be captured at this point, and allow
unwanted users any access the PAT grants. Storing the secret makes it less visible, but it could still be captured in the transfer
or in the moodle-plugin-ci script. For this reason, you will probably want to use your own fork of the plugin-ci script.

```yaml
install:
  - moodle-plugin-ci add-plugin --branch main --token "${{ secrets.REPO_TOKEN }}" username/project
  - moodle-plugin-ci install
```

If you are not using GitHub and want to provide your own Git clone URL, then you can use the `--clone` (`-c`) option.
Here is an example (Note, you can use the `--branch` option together with the `--clone` option if you need to):

```yaml
install:
  - moodle-plugin-ci add-plugin --clone https://bitbucket.org/username/project.git
  - moodle-plugin-ci install
```
