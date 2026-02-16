 # 📘 STANDARD OPERATING PROCEDURE (SOP)

# Tenant Persistence & Schema Governance

**Version:** v1.0.0
**Status:** Production Baseline
**Applies To:** All Tenants, All Environments

---

# 1. PURPOSE

This SOP defines:

* The authoritative schema rules for tenant data
* File system layout for JSON persistence
* Lifecycle-layer separation
* ULID identity rules
* Meta-wrapper contract
* Runtime configuration contract for SPA/PWA
* Validation checklist to ensure user stories are satisfied
* Migration compatibility guarantees (JSON → MySQL)

This is the **single source of truth** for tenant data structure.

---

# 2. GLOBAL NON-NEGOTIABLE RULES

## 2.1 Identity Rule

* All entities MUST use **ULID**
* No integer IDs allowed
* ULID format: 26-character Crockford base32
* ULIDs are immutable
* ULIDs must be generated server-side only

---

## 2.2 One-File-Per-Entity Rule

Each entity instance must be stored in its own JSON file.

❌ Forbidden:

```json
parents: [ {...}, {...} ]
```

✅ Required:

```
parents/
    01HV6X....json
    01HV6Y....json
```

---

## 2.3 Meta-Wrapper Pattern (MANDATORY)

Every entity file must follow this contract:

```json
{
  "id": "01HV6X7ABCDE...",
  "meta": {
    "schema_version": "1.0",
    "entity_version": 1,
    "created_at": "ISO-8601",
    "created_by": "ULID",
    "updated_at": "ISO-8601",
    "updated_by": "ULID",
    "deleted_at": null
  },
  "data": {
    // entity-specific fields
  }
}
```

### Rules

* `meta.entity_version` increments on every update
* `deleted_at` enables soft delete
* No business data allowed in `meta`
* No metadata allowed in `data`

---

# 3. TENANT DIRECTORY STRUCTURE

```
storage/data/tenants/{tenant_ulid}/

    tenant.json
    onboarding.json

    config/
        identity.json
        admission_rules.json
        features.json
        security.json
        locale.json

    ui/
        theme.json
        navigation.json
        layout.json
        labels.json
        dashboard.json

    users/
        staff/
            {ulid}.json
        parents/
            {ulid}.json
        roles.json
        permissions.json

    domain/
        children/
            {ulid}.json
        applications/
            {ulid}.json
        enrollment_cycles/
            {ulid}.json
        notices/
            {ulid}.json

    content/
        pages/
            {ulid}.json
        gallery/
            {ulid}.json

    audit/
        2026-02.log
```

---

# 4. LIFECYCLE LAYER SEPARATION

## LAYER 1 — SYSTEM LIFECYCLE (Platform Level)

* tenant.json
* onboarding.json

Purpose:

* Track onboarding progress
* Activation state
* Plan & feature overrides

---

## LAYER 2 — CONFIGURATION LIFECYCLE (Rarely Changes)

Location: `/config`

Used for:

* Business rule enforcement
* Runtime validation
* Feature gating

Examples:

* admission rules
* age validation
* required documents
* waitlist enabled

These directly impact business logic.

---

## LAYER 3 — UI LIFECYCLE (SPA Runtime Control)

Location: `/ui`

Used for:

* Navigation rendering
* Menu structure
* Labels
* Dashboard widgets
* Theme

These must not contain business logic.

---

## LAYER 4 — DOMAIN LIFECYCLE (Operational Data)

Location: `/domain`

Changes frequently.

Includes:

* Applications
* Children
* Enrollment cycles
* Notices

This is transactional data.

---

## LAYER 5 — USER LIFECYCLE (Authentication & RBAC)

Location: `/users`

Includes:

* Staff
* Parents
* Roles
* Permissions

Passwords must be hashed.
No plaintext secrets.

---

## LAYER 6 — AUDIT LIFECYCLE (Immutable)

Location: `/audit`

Append-only logs.

Never wrapped in meta.
Never modified.
Never deleted.

---

# 5. ENTITY CONTRACT DEFINITIONS

Below are minimum required data contracts.

---

## 5.1 tenant.json

Purpose: Global tenant identity.

`data` must include:

* name
* token (unique)
* status (onboarding | active | suspended)
* plan_type
* timezone
* locale

---

## 5.2 onboarding.json

`data` must include:

* current_step
* completed_steps[]
* is_locked
* submitted_at
* activated_at

Prevents skipping steps.

---

## 5.3 admission_rules.json

Must use structured validation.

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
  ]
}
```

No free-text rules allowed.

---

## 5.4 features.json

Must use namespaced modules:

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

## 5.5 navigation.json

Must support feature gating:

```json
{
  "main_menu": [
    {
      "id": "applications",
      "label": "Applications",
      "route": "/applications",
      "feature_required": "admissions.enabled",
      "role_required": ["creche_admin", "staff"]
    }
  ]
}
```

---

## 5.6 Parent Entity

Must support:

* multiple children
* secure authentication
* notification preferences

---

## 5.7 Child Entity

Must support:

* birth_date
* grade_applied
* linked_parent_id
* document references

---

## 5.8 Application Entity

Must support:

* parent_id
* child_id
* enrollment_cycle_id
* status (pending | accepted | rejected | waitlisted)
* status_history[]
* decision_notes
* reviewed_by

---

# 6. RUNTIME CONFIG AGGREGATION (SPA CONTRACT)

Endpoint:

```
GET /api/runtime-config
```

Returns aggregated:

* tenant identity
* features
* UI config
* permissions
* admission rules

The SPA must not read raw JSON files directly.

Backend aggregates and caches per tenant.

Cache invalidated on config change.

---

# 7. AUDIT CONTRACT

Each entry:

```json
{
  "event_id": "ULID",
  "timestamp": "ISO-8601",
  "tenant_id": "ULID",
  "user_id": "ULID",
  "entity_type": "application",
  "entity_id": "ULID",
  "action": "status_changed",
  "before_hash": "SHA256",
  "after_hash": "SHA256",
  "ip_address": "...",
  "user_agent": "..."
}
```

Append-only.

No edits.

---

# 8. SCHEMA VALIDATION CHECKLIST (MANDATORY)

Before merging any schema change:

✅ ULID used
✅ Meta-wrapper present
✅ No integer IDs
✅ Lifecycle layer respected
✅ Config separate from domain
✅ Audit not embedded
✅ Feature flags namespaced
✅ Admission rules structured
✅ Runtime-config contract unchanged
✅ Supports multi-child parent
✅ Supports application status history
✅ Supports public content without login

---

# 9. USER STORY COVERAGE VALIDATION

## Visitor Stories Covered?

* Public content → content/pages
* Admission guidelines → content/pages
* Gallery → content/gallery
* Contact details → identity.json + pages
* Notices → domain/notices (is_public=true)

✔ Covered.

---

## Parent Stories Covered?

* Register/login → users/parents
* Multiple children → domain/children
* Age validation → config/admission_rules
* Upload documents → child.document references
* Submit application → domain/applications
* Track status → application.status + history
* Notifications → features.modules + notices

✔ Covered.

---

## Admin Stories Covered?

* Secure login → users/staff
* View applications → domain/applications
* Accept/reject → application.status + audit
* Post notices → domain/notices
* Manage content → content/pages
* Update statuses → application entity_version increment

✔ Covered.

No missing functional requirement detected.

---

# 10. MIGRATION GUARANTEE (JSON → MYSQL)

Each entity file maps to:

* One table
* Meta fields → columns
* Data fields → columns
* JSON column for flexible fields
* ULID primary key

No rewrite of business logic if repository pattern respected.

---

# 11. FINAL LOCKED DECISIONS

| Decision            | Status   |
| ------------------- | -------- |
| ULID                | Locked   |
| One-file-per-entity | Locked   |
| Meta-wrapper        | Locked   |
| Append-only audit   | Locked   |
| Layer separation    | Locked   |
| Runtime-config API  | Required |
| Feature namespacing | Required |

---

# 12. GOVERNANCE

 
# 15. SYSTEM REFERENCE LAYER (GLOBAL, TENANT-FILTERABLE)
## 15.1 Purpose

The System Reference Layer defines globally seeded, platform-governed domain reference data that:

* Is shared across all tenants
* Is not tenant-owned
* Is not tenant-editable
* Is referenced by tenant domain entities
* May be filtered per tenant via configuration
* Must remain consistent across environments
* Must be versioned with the platform
This layer ensures:
*  No duplication of static domain lists across tenants
* Clean SQL migration mapping
* Referential integrity across tenant domain entities
* Centralized governance of reference datasets
## 15.2 Location in Directory Structure

Reference entities are stored under:
storage/

    /system/
        reference/
            learning_outcomes/
            study_themes/
            locations/
            (future reference sets)

Each reference entity:
/system/reference/{reference_type}/{ulid}.json
## 15.3 Structural Requirements
All reference entities MUST:

* Use ULID (26-character Crockford Base32)
* Be server-generated
* Be immutable ID
* Follow the Meta-Wrapper Contract
*  NOT include tenant_id
* NOT include membership_id
* NOT include roles
* NOT include permissions
* NOT include tenant-specific overrides

They are global.

## 15.4 Governance Rules

Reference data is:
* Platform-controlled
* Modified only via platform-level administrative process
* Versioned alongside platform releases
* Included in CI validation
* Included in backup procedures
* Included in migration verification
* Tenants MUST NOT modify system reference data directly.

## 15.5 Tenant Filtering Model (Globally Seeded, Tenant-Filterable)

Although reference data is global, tenants may filter allowed values.
Filtering occurs ONLY via tenant configuration.

Example:
/tenants/{tenant_ulid}/config/admission_rules.json
{
  "allowed_learning_outcomes": ["ULID1", "ULID2"],
  "allowed_study_themes": ["ULID5", "ULID9"],
  "allowed_locations": ["ULID12"]
}
Rules:

* Filtering stores ULID references only
* No duplication of reference object
* Validation must confirm referenced ULID exists in system/reference

If referenced ULID does not exist → reject write

## 15.6 Cross-Tenant Safety

Tenant domain entities may reference:

* learning_outcome_id
* study_theme_id
* location_id
Validation MUST:
* Confirm ULID exists in system/reference
* Confirm ULID is allowed by tenant config (if filter exists)

Reference access does NOT violate tenant isolation because:
* Reference entities contain no tenant data
* They are read-only for tenants
* They do not expose cross-tenant domain information

# 15.8 Future Extensibility

Future reference sets may include:

* program_types
* document_types
* grade_levels
* languages
* payment_terms
* country_codes

All must comply with this layer’s rules.