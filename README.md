# PCR Automation with OpenAI Code Review & Refactoring
 
<p align="center">
<a href="https://github.com/bhavinsimform/automation-openai-php/actions"><img src="https://github.com/bhavinsimform/automation-openai-php/actions/workflows/php.yml/badge.svg" alt="Build Status"></a>
</p>

## Description

Code reviews are a crucial part of the development process. However, the manual process of reviewing code changes and generating changelogs can be time-consuming and error-prone. Our project aims to solve these challenges by automating the process of generating PCR with changelogs and performing code reviews.

Our solution leverages AI tools and automation to streamline the process of PCR generation with changelogs. We've used Github pipelines to run the code, fetch the task number from the commit or PR title, and create a Confluence page for the PCR. The Confluence page will include the task title, status, minor description, label, and assignee name.

To generate the changelog, we have used the ChatGPT API, an open AI tool that can generate human-like text. The API will analyze the commit messages and generate a summary of the changes, which we will add to the Confluence page.

For code analysis, We used OpenAI to perform code reviews and provide suggestions for code improvements based on our coding standards.

## Installation

1. Fork and Clone the repository
2. Install dependencies: `composer install`
3. Run index.php inside /src/ folder

## Usage

To use our PCR automation tool, follow these steps:

1. Make sure you have a Bitbucket/Github account with the appropriate permissions.
2. Set up your project in Github and enable Action.
3. Add the necessary scripts to your project's `package.json` file.
4. Commit your code changes and push them to Github.
6. Github Action will automatically run the code, fetch the task number from the commit or PR title, and create a Confluence page for the PCR.
7. The ChatGPT API will analyze the commit messages and generate a summary of the changes, which will be added to the Confluence page.
8. OpenAI will perform code reviews and provide suggestions for code improvements.

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT). See the [LICENSE](LICENSE) file for details.
