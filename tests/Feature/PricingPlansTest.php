<?php

use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->superAdmin = User::create([
        'name' => 'Super Admin',
        'email' => 'super@skulbase.com',
        'password' => bcrypt('password'),
    ]);
    $this->superAdmin->forceFill(['role' => 'super_admin'])->save();
});

it('seeds three plans with correct data', function (): void {
    $this->seed(PlanSeeder::class);

    $starter = Plan::where('slug', 'starter')->first();
    $standard = Plan::where('slug', 'standard')->first();
    $premium = Plan::where('slug', 'premium')->first();

    $this->assertNotNull($starter);
    $this->assertNotNull($standard);
    $this->assertNotNull($premium);

    $this->assertEquals(5000, (float) $starter->monthly_price);
    $this->assertEquals(50000, (float) $starter->yearly_price);
    $this->assertEquals(300, $starter->student_limit);
    $this->assertFalse($starter->is_unlimited);

    $this->assertEquals(10000, (float) $standard->monthly_price);
    $this->assertEquals(100000, (float) $standard->yearly_price);
    $this->assertEquals(1000, $standard->student_limit);
    $this->assertFalse($standard->is_unlimited);

    $this->assertEquals(20000, (float) $premium->monthly_price);
    $this->assertEquals(200000, (float) $premium->yearly_price);
    $this->assertNull($premium->student_limit);
    $this->assertTrue($premium->is_unlimited);
});

it('has 30 day trial for all plans', function (): void {
    $this->seed(PlanSeeder::class);

    Plan::query()->each(function ($plan): void {
        $this->assertEquals(30, $plan->trial_days);
    });
});

it('discount is not active when percentage is zero', function (): void {
    $plan = Plan::create([
        'name' => 'Test Plan',
        'slug' => 'test-zero-discount',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 0,
        'discount_scope' => 'both',
    ]);

    $this->assertFalse($plan->isDiscountActive());
    $this->assertEquals(10000, $plan->discountedPrice('monthly'));
    $this->assertEquals(100000, $plan->discountedPrice('yearly'));
});

it('discount is not active when no dates are set and percentage is zero', function (): void {
    $plan = Plan::create([
        'name' => 'No Discount Plan',
        'slug' => 'no-discount-plan',
        'monthly_price' => 8000,
        'yearly_price' => 80000,
        'student_limit' => 200,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
    ]);

    $this->assertFalse($plan->isDiscountActive());
    $this->assertEquals(8000, $plan->discountedPrice('monthly'));
    $this->assertEquals(80000, $plan->discountedPrice('yearly'));
});

it('calculates active discount with no date restrictions', function (): void {
    $plan = Plan::create([
        'name' => 'Discounted Plan',
        'slug' => 'discounted-plan',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 20,
        'discount_start_date' => null,
        'discount_end_date' => null,
        'discount_scope' => 'both',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(8000, $plan->discountedPrice('monthly'));
    $this->assertEquals(80000, $plan->discountedPrice('yearly'));
});

it('returns normal price when discount is not active for monthly only', function (): void {
    $plan = Plan::create([
        'name' => 'Monthly Only Discount',
        'slug' => 'monthly-only-discount',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 20,
        'discount_scope' => 'monthly',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(8000, $plan->discountedPrice('monthly'));
    $this->assertEquals(100000, $plan->discountedPrice('yearly'));
});

it('returns normal price when discount scope is monthly only but request is yearly', function (): void {
    $plan = Plan::create([
        'name' => 'Scope Monthly',
        'slug' => 'scope-monthly',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 25,
        'discount_scope' => 'monthly',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(7500, $plan->discountedPrice('monthly'));
    $this->assertEquals(100000, $plan->discountedPrice('yearly'));
});

it('returns normal price when discount scope is annual only but request is monthly', function (): void {
    $plan = Plan::create([
        'name' => 'Scope Annual',
        'slug' => 'scope-annual',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 25,
        'discount_scope' => 'annual',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(10000, $plan->discountedPrice('monthly'));
    $this->assertEquals(75000, $plan->discountedPrice('yearly'));
});

it('applies discount to both cycles when scope is both', function (): void {
    $plan = Plan::create([
        'name' => 'Both Scope',
        'slug' => 'both-scope',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 15,
        'discount_scope' => 'both',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(8500, $plan->discountedPrice('monthly'));
    $this->assertEquals(85000, $plan->discountedPrice('yearly'));
});

it('discounted price never becomes negative', function (): void {
    $plan = Plan::create([
        'name' => 'Max Discount',
        'slug' => 'max-discount',
        'monthly_price' => 100,
        'yearly_price' => 1000,
        'student_limit' => 50,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 100,
        'discount_scope' => 'both',
    ]);

    $this->assertTrue($plan->isDiscountActive());
    $this->assertEquals(0, $plan->discountedPrice('monthly'));
    $this->assertEquals(0, $plan->discountedPrice('yearly'));
});

it('expired discount returns normal price', function (): void {
    $plan = Plan::create([
        'name' => 'Expired Discount',
        'slug' => 'expired-discount',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 20,
        'discount_start_date' => now()->subDays(30),
        'discount_end_date' => now()->subDay(),
        'discount_scope' => 'both',
    ]);

    $this->assertFalse($plan->isDiscountActive());
    $this->assertEquals(10000, $plan->discountedPrice('monthly'));
    $this->assertEquals(100000, $plan->discountedPrice('yearly'));
});

it('future discount returns normal price', function (): void {
    $plan = Plan::create([
        'name' => 'Future Discount',
        'slug' => 'future-discount',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 20,
        'discount_start_date' => now()->addDays(10),
        'discount_end_date' => now()->addDays(30),
        'discount_scope' => 'both',
    ]);

    $this->assertFalse($plan->isDiscountActive());
    $this->assertEquals(10000, $plan->discountedPrice('monthly'));
    $this->assertEquals(100000, $plan->discountedPrice('yearly'));
});

it('super admin can access plans index', function (): void {
    $this->actingAs($this->superAdmin);

    $response = $this->get(route('plans.index'));

    $response->assertOk();
    $response->assertSee('Pricing Plans');
});

it('super admin can create a plan', function (): void {
    $this->actingAs($this->superAdmin);

    $response = $this->post(route('plans.store'), [
        'name' => 'New Plan',
        'slug' => 'new-plan',
        'monthly_price' => 7500,
        'yearly_price' => 75000,
        'student_limit' => 400,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 5,
        'discount_percentage' => 10,
        'discount_scope' => 'both',
    ]);

    $response->assertRedirect();

    $plan = Plan::where('slug', 'new-plan')->first();
    $this->assertNotNull($plan);
    $this->assertEquals(7500, (float) $plan->monthly_price);
    $this->assertEquals(10, (float) $plan->discount_percentage);
});

it('super admin can update a plan with discount', function (): void {
    $plan = Plan::create([
        'name' => 'Update Test',
        'slug' => 'update-test',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 200,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
    ]);

    $this->actingAs($this->superAdmin);

    $response = $this->put(route('plans.update', $plan), [
        'name' => 'Update Test',
        'monthly_price' => 6000,
        'yearly_price' => 60000,
        'student_limit' => 300,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
        'discount_percentage' => 25,
        'discount_scope' => 'monthly',
    ]);

    $response->assertRedirect();

    $plan->refresh();
    $this->assertEquals(6000, (float) $plan->monthly_price);
    $this->assertEquals(25, (float) $plan->discount_percentage);
    $this->assertEquals('monthly', $plan->discount_scope);
});

it('registration page shows all three plans', function (): void {
    Plan::create([
        'name' => 'Starter',
        'slug' => 'starter',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 300,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
    ]);
    Plan::create([
        'name' => 'Standard',
        'slug' => 'standard',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 1000,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 2,
    ]);
    Plan::create([
        'name' => 'Premium',
        'slug' => 'premium',
        'monthly_price' => 20000,
        'yearly_price' => 200000,
        'student_limit' => null,
        'is_unlimited' => true,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 3,
    ]);

    $response = $this->get(route('school.register'));

    $response->assertOk();
    $response->assertSee('Starter');
    $response->assertSee('Standard');
    $response->assertSee('Premium');
    $response->assertSee('30-day free trial');
    $response->assertSee('Unlimited students');
});

it('registration page shows discounted prices when active', function (): void {
    Plan::create([
        'name' => 'Discounted Starter',
        'slug' => 'discounted-starter',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 300,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
        'discount_percentage' => 20,
        'discount_scope' => 'both',
    ]);

    $response = $this->get(route('school.register'));

    $response->assertOk();
    $response->assertSee('Discounted Starter');
    $response->assertSee('20% OFF');
});

it('subscription amount_paid preserves original price not plan price', function (): void {
    $school = School::create([
        'name' => 'Test School',
        'slug' => 'test-school-preserve',
        'email' => 'test@preserve.com',
        'status' => 'approved',
    ]);

    $plan = Plan::create([
        'name' => 'Test Plan',
        'slug' => 'test-preserve-price',
        'monthly_price' => 10000,
        'yearly_price' => 100000,
        'student_limit' => 500,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 10,
    ]);

    $subscription = Subscription::create([
        'school_id' => $school->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'status' => 'active',
        'starts_at' => now(),
        'expires_at' => now()->addMonth(),
        'is_trial' => false,
        'amount_paid' => 10000,
    ]);

    $plan->update(['monthly_price' => 15000]);
    $plan->refresh();
    $subscription->refresh();

    $this->assertEquals(15000, (float) $plan->monthly_price);
    $this->assertEquals(10000, (float) $subscription->amount_paid);
});

it('cannot set discount percentage above 100', function (): void {
    $this->actingAs($this->superAdmin);

    $response = $this->post(route('plans.store'), [
        'name' => 'Bad Discount',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 100,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'discount_percentage' => 150,
        'discount_scope' => 'both',
    ]);

    $response->assertSessionHasErrors('discount_percentage');
});

it('format methods show no decimals for whole naira amounts', function (): void {
    $plan = Plan::create([
        'name' => 'Format Test',
        'slug' => 'format-test',
        'monthly_price' => 5000,
        'yearly_price' => 50000,
        'student_limit' => 300,
        'is_unlimited' => false,
        'trial_days' => 30,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $this->assertEquals('₦5,000', $plan->formattedMonthlyPrice());
    $this->assertEquals('₦50,000', $plan->formattedYearlyPrice());
});
