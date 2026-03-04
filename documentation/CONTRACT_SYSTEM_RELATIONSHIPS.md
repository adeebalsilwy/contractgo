# Contract Management System - Complete Data Model & Relationships

## Overview
This document provides a comprehensive overview of all contract-related models, their relationships, and data flow in the Tskify contract management system.

## Core Contract Model

### Contract (`app/Models/Contract.php`)
The central entity that represents a contract in the system.

#### Attributes
- `id`: Primary key
- `workspace_id`: Foreign key to Workspace
- `title`: Contract title/name
- `value`: Contract monetary value
- `start_date`, `end_date`: Contract duration
- `client_id`: Foreign key to Client
- `project_id`: Foreign key to Project
- `contract_type_id`: Foreign key to ContractType
- `profession_id`: Foreign key to Profession
- `description`: Contract description
- `created_by`: Foreign key to User (creator)
- `signed_pdf`: Path to signed PDF
- `site_supervisor_id`: Foreign key to User (site supervisor)
- `quantity_approver_id`: Foreign key to User (quantity approver)
- `accountant_id`: Foreign key to User (accountant)
- `reviewer_id`: Foreign key to User (reviewer)
- `final_approver_id`: Foreign key to User (final approver)
- `workflow_status`: Current workflow status ('draft', 'pending', 'approved', etc.)
- `quantity_approval_status`: Quantity approval status
- `management_review_status`: Management review status
- `accounting_review_status`: Accounting review status
- `final_approval_status`: Final approval status
- `is_archived`: Archive status
- `amendment_requested`: Amendment request flag
- `amendment_reason`: Amendment reason
- `amendment_approved`: Amendment approval status
- `journal_entry_number`, `journal_entry_date`: Financial tracking

#### Relationships
- `workspace()` - Belongs to Workspace
- `client()` - Belongs to Client
- `user()` - Belongs to User (created by)
- `project()` - Belongs to Project
- `contract_type()` - Belongs to ContractType
- `profession()` - Belongs to Profession
- `siteSupervisor()` - Belongs to User (site supervisor)
- `quantityApprover()` - Belongs to User (quantity approver)
- `accountant()` - Belongs to User (accountant)
- `reviewer()` - Belongs to User (reviewer)
- `finalApprover()` - Belongs to User (final approver)
- `amendmentRequestedBy()` - Belongs to User (amendment requested by)
- `amendmentApprovedBy()` - Belongs to User (amendment approved by)
- `archivedBy()` - Belongs to User (archived by)
- `journalEntries()` - Has Many JournalEntry
- `obligations()` - Has Many ContractObligation
- `quantities()` - Has Many ContractQuantity
- `approvals()` - Has Many ContractApproval
- `amendments()` - Has Many ContractAmendment
- `createdBy()` - Belongs to User (created by)
- `invoice()` - Belongs to EstimatesInvoice
- `estimates()` - Has Many EstimatesInvoice (type = 'estimate')
- `invoices()` - Has Many EstimatesInvoice (type = 'invoice')
- `estimatesInvoices()` - Has Many EstimatesInvoice (all types)
- `extracts()` - Has Many EstimatesInvoice (for extracts)
- `tasks()` - Has Many Task
- `contractClients()` - Accessor to get clients through project

## Related Models

### 1. ContractAmendment (`app/Models/ContractAmendment.php`)
Represents amendments requested for a contract.

#### Attributes
- `id`: Primary key
- `contract_id`: Foreign key to Contract
- `requested_by_user_id`: Foreign key to User (requester)
- `approved_by_user_id`: Foreign key to User (approver)
- `amendment_type`: Type of amendment ('price', 'quantity', 'specification', 'other')
- `request_reason`: Reason for amendment
- `details`: Detailed description
- `original_price`, `new_price`: Price values for price amendments
- `original_quantity`, `new_quantity`: Quantity values for quantity amendments
- `original_unit`, `new_unit`: Unit values for quantity amendments
- `original_description`, `new_description`: Description values for specification amendments
- `status`: Amendment status ('pending', 'approved', 'rejected')
- `approval_comments`: Approval/rejection comments
- `approved_at`, `rejected_at`: Timestamps for approval/rejection
- `digital_signature_path`: Path to digital signature
- `signed_at`: Signature timestamp
- `signed_by_user_id`: Foreign key to User (signer)

#### Relationships
- `contract()` - Belongs to Contract
- `requestedBy()` - Belongs to User (requested by)
- `approvedBy()` - Belongs to User (approved by)
- `signedBy()` - Belongs to User (signed by)

### 2. ContractQuantity (`app/Models/ContractQuantity.php`)
Manages quantities for contract items.

#### Attributes
- `id`: Primary key
- `contract_id`: Foreign key to Contract
- `user_id`: Foreign key to User (submitter)
- `workspace_id`: Foreign key to Workspace
- `item_id`: Foreign key to Item
- `item_description`: Description of the item
- `requested_quantity`: Requested quantity
- `approved_quantity`: Approved quantity
- `unit`: Unit of measurement
- `unit_price`: Price per unit
- `total_amount`: Total amount (calculated)
- `notes`: Additional notes
- `supporting_documents`: JSON array of supporting documents
- `status`: Status ('pending', 'approved', 'rejected', 'modified')
- `submitted_at`: Submission timestamp
- `approved_rejected_at`: Approval/rejection timestamp
- `approved_rejected_by`: Foreign key to User (approver/rejector)
- `approval_rejection_notes`: Notes for approval/rejection
- `quantity_approval_signature`: Digital signature

#### Relationships
- `contract()` - Belongs to Contract
- `user()` - Belongs to User (submitter)
- `approvedRejectedBy()` - Belongs to User (approver/rejector)
- `workspace()` - Belongs to Workspace
- `item()` - Belongs to Item

### 3. ContractApproval (`app/Models/ContractApproval.php`)
Manages the approval workflow for contracts.

#### Attributes
- `id`: Primary key
- `contract_id`: Foreign key to Contract
- `approval_stage`: Stage of approval ('quantity', 'management', 'accounting', 'final')
- `approver_id`: Foreign key to User (approver)
- `status`: Approval status ('pending', 'approved', 'rejected')
- `comments`: Approval comments
- `approved_rejected_at`: Approval/rejection timestamp
- `approval_signature`: Digital signature
- `rejection_reason`: Reason for rejection

#### Relationships
- `contract()` - Belongs to Contract
- `approver()` - Belongs to User (approver)

### 4. ContractObligation (`app/Models/ContractObligation.php`)
Manages contractual obligations and responsibilities.

#### Attributes
- `id`: Primary key
- `contract_id`: Foreign key to Contract
- `party_id`: Foreign key to User (responsible party)
- `party_type`: Type of party (not currently used, defaults to User)
- `title`: Obligation title
- `description`: Detailed description
- `obligation_type`: Type of obligation ('compliance', 'delivery', 'payment', 'reporting')
- `priority`: Priority level
- `status`: Status ('pending', 'in_progress', 'completed', 'cancelled')
- `due_date`: Due date for completion
- `completed_date`: Completion date
- `assigned_to`: Foreign key to User (assignee)
- `notes`: Additional notes
- `supporting_documents`: JSON array of supporting documents
- `compliance_status`: Compliance status
- `compliance_notes`: Compliance notes
- `compliance_checked_by`: Foreign key to User (checker)
- `compliance_checked_at`: Compliance check timestamp

#### Relationships
- `contract()` - Belongs to Contract
- `party()` - Belongs to User (responsible party)
- `assignedTo()` - Belongs to User (assignee)
- `complianceCheckedBy()` - Belongs to User (checker)

### 5. JournalEntry (`app/Models/JournalEntry.php`)
Financial journal entries related to contracts.

#### Attributes
- `id`: Primary key
- `contract_id`: Foreign key to Contract
- `invoice_id`: Foreign key to EstimatesInvoice
- `entry_number`: Journal entry number
- `entry_type`: Type of entry
- `entry_date`: Entry date
- `reference_number`: Reference number
- `description`: Description
- `debit_amount`: Debit amount
- `credit_amount`: Credit amount
- `account_code`: Account code
- `account_name`: Account name
- `created_by`: Foreign key to User (creator)
- `status`: Entry status
- `posted_at`: Posting timestamp
- `posted_by`: Foreign key to User (poster)
- `posting_notes`: Posting notes
- `integration_data`: JSON array for integration data
- `workspace_id`: Foreign key to Workspace

#### Relationships
- `contract()` - Belongs to Contract
- `invoice()` - Belongs to EstimatesInvoice
- `createdBy()` - Belongs to User (creator)
- `postedBy()` - Belongs to User (poster)
- `workspace()` - Belongs to Workspace

### 6. EstimatesInvoice (`app/Models/EstimatesInvoice.php`)
Manages estimates and invoices for contracts.

#### Attributes
- `id`: Primary key
- `workspace_id`: Foreign key to Workspace
- `client_id`: Foreign key to Client
- `contract_id`: Foreign key to Contract
- `name`, `address`, `city`, `state`, `country`, `zip_code`, `phone`: Client information
- `type`: Type ('estimate', 'invoice', 'extract')
- `status`: Status of the estimate/invoice
- `note`, `personal_note`: Notes
- `from_date`, `to_date`: Date range
- `total`: Subtotal amount
- `tax_amount`: Tax amount
- `final_total`: Final total amount
- `created_by`: Foreign key to User (creator)

#### Relationships
- `items()` - Belongs to Many Item (via pivot table)
- `payments()` - Has Many Payment
- `client()` - Belongs to Client
- `contract()` - Belongs to Contract
- `project()` - Has One Through Project (via Contract)
- `contractClients()` - Accessor to get clients through contract

### 7. Task (`app/Models/Task.php`)
Tasks that can be associated with contracts.

#### Attributes
- `id`: Primary key
- `title`: Task title
- `status_id`: Foreign key to Status
- `priority_id`: Foreign key to Priority
- `project_id`: Foreign key to Project
- `contract_id`: Foreign key to Contract (optional)
- `start_date`, `due_date`: Task dates
- `description`: Task description
- `note`: Additional note
- `client_can_discuss`: Flag for client discussion
- `user_id`: Foreign key to User (assignee)
- `workspace_id`: Foreign key to Workspace
- `created_by`: Foreign key to User (creator)
- `parent_id`: Self-referencing for subtasks
- `billing_type`: Billing type
- `completion_percentage`: Completion percentage
- `task_list_id`: Foreign key to TaskList

#### Relationships
- `contract()` - Belongs to Contract (optional)
- `project()` - Belongs to Project
- `users()` - Belongs to Many User
- `status()` - Belongs to Status
- `priority()` - Belongs to Priority
- `workspace()` - Belongs to Workspace
- `parent()` - Belongs to Task (self-referencing)
- `subtasks()` - Has Many Task (self-referencing)

## Data Flow & Business Logic

### Contract Lifecycle
1. **Creation**: Contract is created with basic information
2. **Quantities**: Contract quantities are added and go through approval process
3. **Approvals**: Contract goes through multiple approval stages (quantity, management, accounting, final)
4. **Execution**: Contract is executed with tasks, obligations, and financial transactions
5. **Amendments**: Contract amendments can be requested and processed
6. **Completion**: Contract is completed and potentially archived

### Key Business Rules
- Contracts can have multiple quantities that require approval
- Contracts can have multiple obligations with different parties responsible
- Contracts can have multiple estimates and invoices
- Contracts can have associated journal entries for financial tracking
- Contracts can have associated tasks for execution
- Contract amendments must go through approval workflow
- Financial tracking is maintained through journal entries

### Workflow Integration
- Contract approval workflow includes multiple stages
- Each stage can have electronic signatures
- Contract status is tracked through various workflow statuses
- Amendment approval triggers workflow reflow

### Data Integrity
- Soft deletes and archive functionality protect historical data
- Digital signatures ensure document authenticity
- Supporting documents can be attached to various entities
- Comprehensive audit trails through activity logging