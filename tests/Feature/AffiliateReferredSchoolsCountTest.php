<?php

use App\Models\Affiliate;
use App\Models\Referral;
use App\Models\School;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createAffiliateWithUser(string $name = 'Aff1'): array
{
    $user = User::factory()->create(['role' => 'affiliate']);
    $affiliate = Affiliate::factory()->active()->create(['user_id' => $user->id, 'name' => $name]);

    return [$user, $affiliate];
}

function createReferredSchool(Affiliate $affiliate, string $schoolName = 'School', string $status = 'registered'): School
{
    $school = School::create([
        'name' => $schoolName,
        'slug' => Str::slug($schoolName),
        'email' => Str::slug($schoolName).'@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
    ]);

    Referral::create([
        'affiliate_id' => $affiliate->id,
        'school_id' => $school->id,
        'referred_email' => $school->email,
        'status' => $status,
    ]);

    return $school;
}

it('shows 0 referred schools when affiliate has no referrals', function (): void {
    [$user, $affiliate] = createAffiliateWithUser();

    $response = $this->actingAs($user)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(0);
});

it('shows 1 referred school when affiliate has 1 referral with school_id', function (): void {
    [$user, $affiliate] = createAffiliateWithUser();
    createReferredSchool($affiliate, 'Alpha School');

    $response = $this->actingAs($user)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(1);
});

it('shows 2 referred schools when affiliate has 2 referrals with school_id', function (): void {
    [$user, $affiliate] = createAffiliateWithUser();
    createReferredSchool($affiliate, 'Alpha School');
    createReferredSchool($affiliate, 'Beta School');

    $response = $this->actingAs($user)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(2);
});

it('does not count another affiliate referred schools', function (): void {
    [$userA, $affiliateA] = createAffiliateWithUser('AffA');
    [$userB, $affiliateB] = createAffiliateWithUser('AffB');

    createReferredSchool($affiliateA, 'School A1');
    createReferredSchool($affiliateA, 'School A2');
    createReferredSchool($affiliateB, 'School B1');

    $response = $this->actingAs($userA)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(2);
});

it('counts referred schools created via handleSchoolRegistration', function (): void {
    [$user, $affiliate] = createAffiliateWithUser();

    $school = School::create([
        'name' => 'Via Service School',
        'slug' => 'via-service-school',
        'email' => 'via@test.com',
        'is_active' => true,
        'registration_status' => 'approved',
    ]);

    $affiliateService = app(AffiliateService::class);
    $referral = $affiliateService->handleSchoolRegistration($school, $affiliate->code);

    expect($referral)->not->toBeNull();
    expect($referral->school_id)->toBe($school->id);

    $response = $this->actingAs($user)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(1);
});

it('does not count referrals without school_id in referred_schools', function (): void {
    [$user, $affiliate] = createAffiliateWithUser();

    createReferredSchool($affiliate, 'With School');

    Referral::create([
        'affiliate_id' => $affiliate->id,
        'referred_email' => 'no-school@test.com',
        'status' => 'registered',
    ]);

    $response = $this->actingAs($user)->get(route('affiliate.dashboard'));

    $response->assertOk();

    $stats = $response->viewData('stats');

    expect($stats['referred_schools'])->toBe(1);
});
