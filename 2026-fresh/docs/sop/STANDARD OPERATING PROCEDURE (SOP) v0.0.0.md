# 📘 STANDARD OPERATING PROCEDURE (SOP)

## Multi-Tenant Crèche Website Platform

**Document Title:** Platform Development, Onboarding & Operations SOP
**Version:** v0.0.0
**Status:** Initial Baseline
**Date:** 26 January 2026
**Prepared For:** Crèche Website Platform Team
**Prepared By:** Platform Team (BA, Dev, PM, QA)

---

## 1. PURPOSE

The purpose of this SOP is to define **standard, repeatable, and secure procedures** for:

* Developing the multi-tenant crèche website platform
* Onboarding crèches efficiently and safely
* Enforcing strong RBAC and data isolation
* Managing environments, DevOps, and progress tracking
* Building confidence with early adopters through consistency, security, and auditability

This SOP establishes a **single source of truth** for how the platform is built and operated.

---

## 2. SCOPE

This SOP applies to:

* All platform developers and interns
* Business analysts and project managers
* QA and DevOps activities
* All environments (local, dev, staging, production)
* All crèches onboarded onto the platform

---

## 3. PLATFORM OVERVIEW

The platform is a **multi-tenant crèche website system** where:

* One shared core codebase serves multiple crèches
* Each crèche is isolated by tenant configuration and data
* Crèche-specific behavior is driven by configuration, not custom code
* Initial storage uses JSON, with a planned migration to MySQL

The platform supports rapid onboarding (target: **~10 crèches per week**).

---

## 4. CORE PRINCIPLES (NON-NEGOTIABLE)

The following principles **must always be enforced**:

1. Configuration over custom code
2. One shared core platform (no forks)
3. Strong Role-Based Access Control (RBAC)
4. Business rules outside controllers
5. Test-driven development (TDD)
6. Tenant isolation as a first-class concern
7. Onboarding speed is a feature
8. Security and privacy are non-negotiable
9. Human-centric UX
10. AI is a copilot, not the decision-maker

Any deviation from these principles requires explicit review and approval.

---

## 5. USER ROLES & RESPONSIBILITIES (RBAC BASELINE)

### 5.1 Defined Roles

| Role                 | Scope         | Summary Responsibility                  |
| -------------------- | ------------- | --------------------------------------- |
| System Admin         | Global        | Platform management, onboarding, audits |
| Crèche Administrator | Single tenant | Admissions, staff, content              |
| Staff                | Single tenant | Operational support                     |
| Parent               | Own data      | Applications and tracking               |
| Guest                | Public        | View public content                     |
| Moderator (Future)   | Multi-tenant  | Oversight and compliance                |

### 5.2 RBAC Rules

* Deny-by-default policy
* Role ≠ permission (roles aggregate permissions)
* Every action scoped to a tenant
* All sensitive actions are auditable
* Backend enforces all access control

RBAC must work identically whether data is stored in JSON or MySQL.

---

## 6. ARCHITECTURE & DEVELOPMENT STANDARDS

### 6.1 Technical Architecture

The platform uses a lightweight modular HTTP architecture:

* Custom PSR-7 compatible HTTP kernel
* Bramus Router for route handling
* Middleware pipeline (PSR-15 style)
* Thin controllers
* Response abstraction (HTML/JSON)
* Blade templating for server-rendered views
* Business logic isolated in services
* Repository pattern for storage abstraction
* The architecture is framework-agnostic and storage-agnostic.
* Follow Laravel conventions naming and folder structure  where possible  

### 6.2 Storage Strategy

**Phase 1:** JSON-based storage
**Phase 2:** MySQL (no business logic rewrite)

The application must not depend directly on storage format.

---

## 7. DEVELOPMENT METHODOLOGY (SOP)

### 7.1 Test-Driven Development (TDD)

All development follows this loop:

1. Write a failing test
2. Run tests → fail
3. Write minimum code to pass
4. Run tests → pass
5. Refactor

Tests define:

* System behavior
* Business rules
* Acceptance criteria

No feature is considered complete without tests.

---

## 8. ONBOARDING PROCESS (CRÈCHE LIFECYCLE)

Onboarding is treated as a **pipeline**, not an ad-hoc task.

### 8.1 Stage 1: Pre-Onboarding (Intake)

* Stakeholder confirmation
* Onboarding checklist completed
* Admission rules confirmed
* Branding and content inputs collected
* Crèche administrator identified

**Output:** Approved onboarding package

---

### 8.2 Stage 2: Tenant Creation (Automated)

* Generate unique tenant ID
* Create tenant folder / records
* Initialize config, content, audit logs
* Create admin account

This step is executed via script/command (no manual setup).

---

### 8.3 Stage 3: Configuration

* Admission rules
* Age eligibility
* Enrolment cycle
* Required documents
* Feature toggles

All configuration is tenant-scoped and versioned.

---

### 8.4 Stage 4: Static Content Initialization

* Default templates copied
* Crèche-specific values injected
* Content editable later via admin UI

No developer involvement required for text updates.

---

### 8.5 Stage 5: Access Control Validation

* Roles assigned
* Password reset enforced
* Permission checks validated

No crèche proceeds without RBAC verification.

---

### 8.6 Stage 6: Validation & Smoke Testing

Automated and manual checks:

* Tenant isolation
* Parent application flow
* Admin review flow
* Document uploads
* Notifications

---

### 8.7 Stage 7: Go-Live & Handover

* Admin credentials issued
* Quick-start guide shared
* Support contact confirmed
* Go-live recorded in audit log

---

## 9. DEVOPS & ENVIRONMENTS

### 9.1 Environments

| Environment | Purpose            |
| ----------- | ------------------ |
| Local       | Developer work     |
| Dev         | Shared integration |
| Staging     | Pre-production     |
| Production  | Live crèches       |

Environment parity is mandatory.

---

### 9.2 CI/CD (High-Level)

* Automated testing
* Secure deployments
* Rollbacks supported
* No direct production changes

(Detailed CI/CD checklist to follow in next version.)

---

## 10. LOGGING, AUDITING & SECURITY

### 10.1 Audit Logging

Every sensitive action records:

* Timestamp
* Tenant ID
* User ID
* Action
* Before/after state

Audit logs are immutable.

---

### 10.2 Security Controls

* Least privilege access
* Encrypted sensitive data
* Secure file uploads
* No shared tenant data
* No secrets in source control

---

## 11. PROGRESS TRACKING & GOVERNANCE

### 11.1 Work Tracking

* User stories → tasks
* Tasks linked to tests
* Tasks tracked via GitHub/Jira

### 11.2 Definition of Done

A task is done only when:

* Code merged
* Tests passing
* Deployed to correct environment
* Documented if required

---

## 12. CHANGE & VERSION MANAGEMENT

* Platform changes affect all tenants
* Tenant config/content changes are isolated
* All changes logged and reversible

---

## 13. REVIEW & EVOLUTION

This SOP is a **living document**.

* Changes require version increment
* Reviews occur after onboarding milestones
* Feedback from early adopters feeds improvements

---

## 14. NEXT STEPS

* Create **CI/CD checklist**
* Define **permission matrix**
* Finalise **onboarding scripts**
* Add **incident response SOP**
* Prepare **v0.1.0** after first live crèches

---


Onboarding must guarantee go-live readiness:

* Public visitor page renders correctly ✅
* Admissions flow works ✅
* Admin login possible ✅
* Minimal branding is applied ✅

This is exactly the System Layer responsibility, not tenant-level config.

All of the above schemas support tenant bootstrap. Once onboarding is complete, tenants own tenant-specific config (full admission rules, features, branding), but the initial minimal seed comes from the System

---

# 🟪 FRANCHISE / TENANT GROUPING ARCHITECTURE

3 logical levels:

1️⃣ Platform (global system)
2️⃣ Franchise / Cluster (optional grouping layer)
3️⃣ Tenant (individual crèche)
This becomes:

```
Platform
   └── Franchise (optional)
          └── Tenant
```
## Franchise / Cluster Layer (System Governance Layer)

A Franchise (or Cluster) is:

> A system-level grouping of tenants that share governance, branding policy, feature configuration baselines, reporting aggregation, or oversight roles.

Characteristics:

* Created and managed by System Admin
* Can own multiple tenants
* May define:

  * Default feature presets
  * Default branding presets
  * Reporting visibility
  * Oversight roles (future Moderator role)
* Does NOT replace tenant isolation
* Does NOT bypass tenant RBAC
* Does NOT merge tenant data

---

# 🏗 Where It Lives Architecturally

📁 `/system/franchises/`

NOT under `/tenants/`

Because:

* It is cross-tenant
* It affects multiple tenants
* It must exist before tenant creation (optional assignment during onboarding)

Governance Rules (Must Be Enforced)

Repository Layer must:
* Validate franchise exists
* Validate franchise status = active
* Prevent deleting franchise if tenants reference it
* Prevent franchise accessing tenant data directly
* Apply preset only during onboarding unless explicitly synced

##🧠 Runtime Configuration Resolution Order

When platform boots tenant runtime config:
* Load Franchise (if exists)
* Load Franchise Profiles (if set)
* Load Tenant Config
* Merge (tenant overrides franchise defaults)
* Cache result
* Deterministic and reversible.
## ✅ Franchise System Layer Complete
* System Identity
* System Onboarding
* System Reference
* System Franchise
* Tenant Config

---


### ✅ END OF SOP — VERSION v0.0.0
 