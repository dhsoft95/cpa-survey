{{-- resources/views/filament/components/ei-score-chart.blade.php --}}
<div class="space-y-4">
    <h3 class="text-lg font-medium">Emotional Intelligence Profile</h3>

    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex justify-between items-center mb-2">
            <span class="font-medium text-gray-700">Total EI Score:</span>
            <span class="text-xl font-bold text-blue-600">{{ $totalScore }}</span>
        </div>

        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden mb-4">
            <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, ($totalScore / 350) * 100) }}%"></div>
        </div>
    </div>

    <div class="grid gap-3">
        @foreach($scores as $key => $data)
            <div class="bg-white p-3 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-medium text-gray-700">{{ $data['name'] }}</span>
                    <span class="text-sm font-semibold">{{ $data['score'] }}/{{ $data['max'] }}</span>
                </div>

                <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full"
                         style="width: {{ $data['percent'] }}%;
                               background-color: {{ match($key) {
                                   'esa' => '#3B82F6', // blue
                                   'ee' => '#8B5CF6',  // purple
                                   'eao' => '#EC4899', // pink
                                   'er' => '#10B981',  // green
                                   'esm' => '#F59E0B', // amber
                                   'emo' => '#EF4444', // red
                                   'esc' => '#6366F1',  // indigo
                                   default => '#888',
                               } }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
        <p>
            <span class="font-medium">About the GENOS EI Model:</span> This assessment measures 7 key dimensions of
            emotional intelligence in a workplace context.
        </p>
    </div>
</div>
