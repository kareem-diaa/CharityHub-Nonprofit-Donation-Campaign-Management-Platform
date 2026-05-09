<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add stripe_subscription_id to donations.
     * This column stores the Stripe Subscription ID returned on the first
     * recurring checkout, and is used by the webhook to match invoice.paid
     * and subscription.deleted events back to the correct campaign/user.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            // Nullable: one-time donations will always have null here
            $table->string('stripe_subscription_id')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('stripe_subscription_id');
        });
    }
};
