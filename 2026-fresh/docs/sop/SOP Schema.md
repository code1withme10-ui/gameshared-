 

> 📘 SOP v1.0.0 — Tenant Persistence & Schema Governance

This version:

* Is fully aligned with:

  * **SOP v3.0.0 — Tenant Persistence, Identity & Schema Governance**
  * **SOP v2.0.0-A — Identity, Tenant Resolution & UX Governance**
* Removes architectural conflicts
* Enforces global identity + membership binding model
* Removes tenant-scoped users (now global users)
* Eliminates role/permission duplication
* Aligns runtime-config with active membership model
* Adds concurrency, atomic write, and cross-tenant guard enforcement
* Hardens snapshot, indexing, retention, and validation
* Locks schema for repository implementation phase

You are now safe to begin repository and model creation after this.

# 📘 STANDARD OPERATING PROCEDURE (SOP)

# Tenant Persistence & Schema Governance

**Version:** v1.0.0
**Status:** Production Baseline (Schema Locked)
**Applies To:** All Tenants, All Environments
**Aligned With:**

* SOP v3.0.0 — Tenant Persistence, Identity & Schema Governance
* SOP v2.0.0-A — Identity, Tenant Resolution & UX Governance

---

# 1. PURPOSE

This SOP defines the **authoritative tenant-scoped schema contract** for JSON persistence.

It governs:

* Tenant directory structure
* Lifecycle layer separation
* Entity contracts
* Cross-tenant isolation rules
* Runtime configuration aggregation
* Audit format
* Snapshot requirements
* Concurrency expectations
* JSON → SQL migration guarantees

This document is the **schema lock baseline** prior to repository and model implementation.

---

# 2. GLOBAL ALIGNMENT RULES

This SOP inherits and enforces:

* ULID identity rules (from v3.0.0)
* Meta-wrapper contract (v3.0.0)
* Atomic write requirements (v3.0.0)
* Optimistic concurrency (entity_version)
* Global identity + tenant_membership model (v2.0.0-A)
* Role Registry governance (v3.0.0)

This document does NOT redefine identity rules.

---

# 3. GLOBAL NON-NEGOTIABLE RULES

## 3.1 ULID Identity Rule

All entities:

* MUST use ULID
* MUST be 26-character Crockford Base32
* MUST be server-generated
* MUST be immutable
* MUST NOT use integer IDs

---

## 3.2 Meta-Wrapper Contract (MANDATORY)

Every entity file MUST follow:

```json
{
  "id": "ULID",
  "meta": {
    "schema_version": "1.0.0",
    "entity_version": 1,
    "created_at": "ISO-8601",
    "created_by": "ULID|null",
    "updated_at": "ISO-8601",
    "updated_by": "ULID|null",
    "deleted_at": null
  },
  "data": {}
}
```

### Enforcement Rules

* entity_version increments on every update
* deleted_at enables soft delete
* No business fields inside meta
* No metadata inside data
* Updates MUST enforce optimistic locking

---

## 3.3 Atomic Write Requirement

All writes MUST:

1. Write to temp file
2. fsync
3. Atomic rename
4. Increment entity_version

Silent overwrite is forbidden.

---

# 4. TENANT DIRECTORY STRUCTURE (ALIGNED MODEL)

⚠️ Identity is global.
Tenant directories MUST NOT contain user accounts or roles.

```
storage/

    /system/
        users/
        tenants/
        tenant_memberships/
        role_registry.json
        login_audit/

    /tenants/
        {tenant_ulid}/

            onboarding.json

            config/
                admission_rules.json
                features.json
                security.json
                locale.json
                branding.json

            ui/
                theme.json
                navigation.json
                layout.json
                dashboard.json
                labels.json

            domain/
                parent_profiles/
                children/
                applications/
                enrollment_cycles/
                notices/

            content/
                pages/
                gallery_items/

            audit/
                YYYY-MM.log

    /indexes/
        {tenant_ulid}/
```

---

# 5. LIFECYCLE LAYER SEPARATION

---

## LAYER 1 — SYSTEM LIFECYCLE (GLOBAL)

Located in `/system`.

Includes:

* users
* tenants
* tenant_memberships
* role_registry
* login_audit

Tenant data must never redefine identity.

---

## LAYER 2 — TENANT CONFIG LIFECYCLE

Location: `/config`

Characteristics:

* Rarely changes
* Drives validation logic
* Drives feature gating
* Drives runtime-config

Examples:

* admission rules
* feature modules
* security settings
* locale
* branding

---

## LAYER 3 — TENANT UI LIFECYCLE

Location: `/ui`

Controls SPA rendering only.

Contains:

* navigation
* layout
* dashboard widgets
* theme
* labels

Must NOT contain:

* role logic
* permission logic
* business rules

---

## LAYER 4 — TENANT DOMAIN LIFECYCLE

Transactional data.

ALL domain entities MUST include:

```json
"tenant_id": "ULID"
```

Cross-tenant write attempts MUST be rejected.

---

## LAYER 5 — TENANT AUDIT LIFECYCLE

Append-only log files.

* Not wrapped in meta
* Not editable
* Not deletable

---

# 6. ENTITY CONTRACT DEFINITIONS (TENANT-SCOPED)

---

## 6.1 onboarding.json

Purpose: Tenant activation tracking.

Must include:

* current_step
* completed_steps[]
* is_locked
* submitted_at
* activated_at

Must NOT include plan or identity fields (those belong in system tenant entity).

---

## 6.2 admission_rules.json

Structured validation only.

Example:

```json
{
  "age_policy": {
    "min_months": 24,
    "max_months": 60,
    "enforced": true
  },
  "required_documents": [
    "birth_certificate",
    "immunization_record"
  ],
  "enrollment_cycle_required": true
}
```

No free-text logic.

---

## 6.3 features.json

Namespaced modules required:

```json
{
  "modules": {
    "admissions": {
      "enabled": true,
      "waitlist": true,
      "document_uploads": true
    },
    "gallery": {
      "enabled": true
    },
    "notices": {
      "enabled": true
    }
  }
}
```

---

## 6.4 navigation.json

Must align with Role Registry names.

Example:

```json
{
  "main_menu": [
    {
      "id": "applications",
      "label": "Applications",
      "route": "/applications",
      "feature_required": "admissions.enabled",
      "role_required": ["ADMIN", "STAFF"],
      "permission_required": "applications.view"
    }
  ]
}
```

Roles must exist in `/system/role_registry.json`.

---

## 6.5 parent_profile (Tenant-Scoped)

Represents tenant-specific profile.

Must include:

* tenant_id
* user_id
* address
* emergency_contact
* notification_preferences

Parent authentication is global user.

---

## 6.6 child (Tenant-Scoped)

Must include:

* tenant_id
* parent_profile_id
* birth_date
* grade_applied
* document_refs[]
* medical_notes (optional)
* archived (boolean)

---

## 6.7 application (Tenant-Scoped, Snapshot Required)

Must include:

* tenant_id
* parent_membership_id
* enrollment_cycle_id
* child_snapshot
* parent_snapshot
* emergency_contact_snapshot
* status
* status_history[]
* reviewed_by_membership_id
* decision_notes
* archived

### Snapshot Rules

* Snapshots immutable
* Must store schema_version inside snapshot
* Never overwritten

---

## 6.8 enrollment_cycle

Must include:

* tenant_id
* name
* start_date
* end_date
* capacity
* status (open | closed | archived)

---

## 6.9 notice

Must include:

* tenant_id
* title
* content
* is_public
* publish_at
* expires_at

---

# 7. CROSS-TENANT GUARD RULE

On every write:

* membership.tenant_id MUST equal entity.tenant_id
* Foreign references MUST belong to same tenant
* Mismatch → 403 + audit log

---

# 8. RUNTIME CONFIG CONTRACT (SPA)

Endpoint:

```
GET /api/runtime-config
```

Must aggregate:

* tenant identity (from system/tenants)
* membership roles
* feature modules
* UI config
* permission matrix
* admission rules

Frontend must not read JSON files directly.

Cache invalidated on:

* config change
* membership change
* role change

---

# 9. AUDIT CONTRACT (TENANT DOMAIN EVENTS)

Append-only structure:

```json
{
  "event_id": "ULID",
  "timestamp": "ISO-8601",
  "tenant_id": "ULID",
  "membership_id": "ULID",
  "entity_type": "application",
  "entity_id": "ULID",
  "action": "status_changed",
  "before_hash": "SHA256",
  "after_hash": "SHA256",
  "ip_address": "...",
  "user_agent": "..."
}
```

---

# 10. INDEXING STRATEGY

Optional per-tenant index files:

* Must only contain ULID references
* Must be rebuildable
* Must not duplicate business data

Corruption → rebuild from domain entities.

---

# 11. SCHEMA VALIDATION CHECKLIST (LOCK REQUIRED)

Before repository implementation:

✅ ULID used
✅ Meta-wrapper present
✅ entity_version implemented
✅ No tenant-scoped users
✅ Roles only in tenant_membership
✅ navigation roles match Role Registry
✅ tenant_id present in all domain entities
✅ Snapshot immutable
✅ Cross-tenant guard enforced
✅ Atomic write enforced
✅ Runtime-config contract aligned

---

# 12. JSON → MYSQL MIGRATION GUARANTEE

Each entity file maps to:

* One table
* ULID primary key
* Meta columns
* Data columns
* tenant_id indexed
* Soft delete supported

No business logic rewrite required.

---

# 13. FINAL LOCKED DECISIONS

| Decision                | Status |
| ----------------------- | ------ |
| Global identity model   | Locked |
| Membership binding      | Locked |
| ULID                    | Locked |
| One-file-per-entity     | Locked |
| Meta-wrapper            | Locked |
| Tenant-scoped domain    | Locked |
| Role Registry global    | Locked |
| Runtime-config required | Locked |

---

# 14. CONFLICT REVIEW

### Conflicts Removed from Previous Version

❌ Removed tenant-level `/users/staff` and `/users/parents`
→ Identity is global under `/system/users`

❌ Removed tenant-level roles.json & permissions.json
→ Roles governed globally via Role Registry

❌ Removed tenant.json from tenant directory
→ Tenant entity lives under `/system/tenants`



---

# ✅ SCHEMA IS NOW LOCKED
 
