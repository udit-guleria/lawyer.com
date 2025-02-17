# Data Models

## User
- **id**: string
- **name**: string
- **email**: string
- **type**: enum
  - sales rep
  - manager
  - admin
- **password**: string *(optional - alternative: sign up with Google)*

## Lead
- **id**: string
- **name**: string
- **email**: string
- **phone**: string
- **industry**: string
- **company/institution**: string
- **status**: enum
  - new
  - contacted
  - proposal
  - negotiation
  - closed-won
  - closed-lost

## Task
- **id**: string
- **title**: string
- **description**: string
- **relations**:
  - **lead**: lead id
  - **created by**: user id
  - **assigned to**: array of user ids
- **due date**: date
- **priority**: enum
  - low
  - medium
  - high
- **status**: enum
  - new
  - in-progress
  - completed
  - dropped
  - overdue