<div wire:poll.5s>
    <div class="mb-3">
        <small class="text-muted">Raised: ${{ number_format($campaign->raised_amount, 2) }} / ${{ number_format($campaign->goal_amount, 2) }}</small>
        <div class="progress mt-1" style="height: 10px;">
            @php
                $percent = $campaign->goal_amount > 0 ? ($campaign->raised_amount / $campaign->goal_amount) * 100 : 0;
                $percent = $percent > 100 ? 100 : $percent;
            @endphp
            <div class="progress-bar progress-bar-custom bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
