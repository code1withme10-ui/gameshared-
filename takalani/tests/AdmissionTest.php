<?php

use PHPUnit\Framework\TestCase;

class AdmissionTest extends TestCase
{
    private array $admissions;

    protected function setUp(): void
    {
        $json = file_get_contents(__DIR__ . '/../data/admissions.json');
        $this->admissions = json_decode($json, true);
    }

    public function test_admissions_file_loads()
    {
        $this->assertIsArray($this->admissions);
        $this->assertNotEmpty($this->admissions);
    }

    public function test_application_has_application_id()
    {
        $application = $this->admissions[0];
        $this->assertArrayHasKey('applicationID', $application);
    }

    public function test_application_has_children()
    {
        $application = $this->admissions[0];
        $this->assertArrayHasKey('children', $application);
        $this->assertIsArray($application['children']);
        $this->assertGreaterThan(0, count($application['children']));
    }

    public function test_child_has_status()
    {
        $child = $this->admissions[0]['children'][0];
        $this->assertArrayHasKey('status', $child);
        $this->assertContains($child['status'], ['Pending', 'Admitted', 'Rejected']);
    }

    public function test_child_status_can_be_updated()
    {
        $this->admissions[0]['children'][0]['status'] = 'Admitted';
        $this->assertEquals('Admitted', $this->admissions[0]['children'][0]['status']);
    }

    public function test_invalid_status_is_not_allowed()
    {
        $invalidStatus = 'Approved';
        $allowed = ['Pending', 'Admitted', 'Rejected'];

        $this->assertNotContains($invalidStatus, $allowed);
    }
}

