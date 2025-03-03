@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ $survey->title }} - Analytics</h1>
            <div>
                <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-gray-700 mr-2">Back</a>
                <a href="{{ route('filament.admin.resources.surveys.index') }}" class="px-4 py-2 bg-blue-500 rounded-lg hover:bg-blue-600 text-white">Admin Dashboard</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Survey Overview Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Survey Overview</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Responses:</span>
                        <span class="font-semibold">{{ $responses->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Average EI Score:</span>
                        <span class="font-semibold">{{ $totalScoreData['avg'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Score Range:</span>
                        <span class="font-semibold">{{ $totalScoreData['min'] }} - {{ $totalScoreData['max'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">CPA Members:</span>
                        <span class="font-semibold">{{ $responses->where('demographic_data.is_cpa_member', 'yes')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Winners:</span>
                        <span class="font-semibold">{{ $responses->where('is_winner', true)->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Score Distribution -->
            <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Total EI Score Distribution</h2>
                <div id="totalScoreChart" style="height: 250px;"></div>
            </div>
        </div>

        <!-- Category Stats Cards -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">EI Category Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($categoryStats as $code => $stats)
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">{{ $stats['name'] }}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Average:</span>
                                <span class="font-semibold">{{ $stats['avg'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Median:</span>
                                <span class="font-semibold">{{ $stats['median'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Range:</span>
                                <span class="font-semibold">{{ $stats['min'] }} - {{ $stats['max'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Std Deviation:</span>
                                <span class="font-semibold">{{ $stats['std_dev'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Category Averages Chart -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">EI Category Averages</h2>
            <div id="categoryAveragesChart" style="height: 350px;"></div>
        </div>

        <!-- Demographic Breakdowns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Gender Breakdown -->
            @if(count($genderBreakdown) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scores by Gender</h2>
                    <div id="genderBreakdownChart" style="height: 300px;"></div>
                </div>
            @endif

            <!-- Age Breakdown -->
            @if(count($ageBreakdown) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scores by Age Group</h2>
                    <div id="ageBreakdownChart" style="height: 300px;"></div>
                </div>
            @endif

            <!-- Industry Breakdown -->
            @if(count($industryBreakdown) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scores by Industry</h2>
                    <div id="industryBreakdownChart" style="height: 300px;"></div>
                </div>
            @endif

            <!-- Years Since Designation Breakdown -->
            @if(count($yearsDesignationBreakdown) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scores by Years Since Designation</h2>
                    <div id="yearsDesignationChart" style="height: 300px;"></div>
                </div>
            @endif
        </div>

        <!-- Job Title Breakdown -->
        @if(count($jobTitleBreakdown) > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Scores by Job Title</h2>
                <div id="jobTitleChart" style="height: 400px;"></div>
            </div>
        @endif

        <!-- Category Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @foreach($categoryStats as $code => $stats)
                @if(count($stats['distribution']) > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $stats['name'] }} Score Distribution</h2>
                        <div id="distribution{{ ucfirst($code) }}Chart" style="height: 250px;"></div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Total Score Distribution Chart
                @if(count($totalScoreData['distribution']) > 0)
                var totalScoreOptions = {
                    series: [{
                        name: 'Responses',
                        data: [
                            @foreach($totalScoreData['distribution'] as $data)
                                {{ $data['count'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 250,
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: [
                            @foreach($totalScoreData['distribution'] as $data)
                                '{{ $data['range'] }}',
                            @endforeach
                        ],
                        title: {
                            text: 'Score Range'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Responses'
                        }
                    },
                    colors: ['#3B82F6'],
                };
                var totalScoreChart = new ApexCharts(document.querySelector("#totalScoreChart"), totalScoreOptions);
                totalScoreChart.render();
                @endif

                // Category Averages Chart
                var categoryAveragesOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($categoryStats as $code => $stats)
                                {{ $stats['avg'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($categoryStats as $code => $stats)
                                '{{ $stats['name'] }}',
                            @endforeach
                        ],
                        title: {
                            text: 'EI Categories'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Average Score'
                        }
                    },
                    colors: ['#10B981'],
                };
                var categoryAveragesChart = new ApexCharts(document.querySelector("#categoryAveragesChart"), categoryAveragesOptions);
                categoryAveragesChart.render();

                // Gender Breakdown Chart
                @if(count($genderBreakdown) > 0)
                var genderBreakdownOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($genderBreakdown as $data)
                                {{ $data['avg_score'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($genderBreakdown as $data)
                                '{{ ucfirst($data['category']) }} ({{ $data['count'] }})',
                            @endforeach
                        ],
                        title: {
                            text: 'Gender'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Average Score'
                        }
                    },
                    colors: ['#8B5CF6'],
                };
                var genderBreakdownChart = new ApexCharts(document.querySelector("#genderBreakdownChart"), genderBreakdownOptions);
                genderBreakdownChart.render();
                @endif

                // Age Breakdown Chart
                @if(count($ageBreakdown) > 0)
                var ageBreakdownOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($ageBreakdown as $data)
                                {{ $data['avg_score'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($ageBreakdown as $data)
                                '{{ $data['category'] }} ({{ $data['count'] }})',
                            @endforeach
                        ],
                        title: {
                            text: 'Age Group'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Average Score'
                        }
                    },
                    colors: ['#EC4899'],
                };
                var ageBreakdownChart = new ApexCharts(document.querySelector("#ageBreakdownChart"), ageBreakdownOptions);
                ageBreakdownChart.render();
                @endif

                // Industry Breakdown Chart
                @if(count($industryBreakdown) > 0)
                var industryBreakdownOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($industryBreakdown as $data)
                                {{ $data['avg_score'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($industryBreakdown as $data)
                                '{{ ucfirst($data['category']) }} ({{ $data['count'] }})',
                            @endforeach
                        ],
                        title: {
                            text: 'Industry'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Average Score'
                        }
                    },
                    colors: ['#F59E0B'],
                };
                var industryBreakdownChart = new ApexCharts(document.querySelector("#industryBreakdownChart"), industryBreakdownOptions);
                industryBreakdownChart.render();
                @endif

                // Years Since Designation Chart
                @if(count($yearsDesignationBreakdown) > 0)
                var yearsDesignationOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($yearsDesignationBreakdown as $data)
                                {{ $data['avg_score'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($yearsDesignationBreakdown as $data)
                                '{{ $data['category'] }} ({{ $data['count'] }})',
                            @endforeach
                        ],
                        title: {
                            text: 'Years Since Designation'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Average Score'
                        }
                    },
                    colors: ['#6366F1'],
                };
                var yearsDesignationChart = new ApexCharts(document.querySelector("#yearsDesignationChart"), yearsDesignationOptions);
                yearsDesignationChart.render();
                @endif

                // Job Title Chart
                @if(count($jobTitleBreakdown) > 0)
                var jobTitleOptions = {
                    series: [{
                        name: 'Average Score',
                        data: [
                            @foreach($jobTitleBreakdown as $data)
                                {{ $data['avg_score'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 400
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true,
                        }
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: [
                            @foreach($jobTitleBreakdown as $data)
                                '{{ ucfirst($data['category']) }} ({{ $data['count'] }})',
                            @endforeach
                        ],
                        title: {
                            text: 'Average Score'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Job Title'
                        }
                    },
                    colors: ['#EF4444'],
                };
                var jobTitleChart = new ApexCharts(document.querySelector("#jobTitleChart"), jobTitleOptions);
                jobTitleChart.render();
                @endif

                // Category Distribution Charts
                @foreach($categoryStats as $code => $stats)
                @if(count($stats['distribution']) > 0)
                var distribution{{ ucfirst($code) }}Options = {
                    series: [{
                        name: 'Responses',
                        data: [
                            @foreach($stats['distribution'] as $data)
                                {{ $data['count'] }},
                            @endforeach
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 250,
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: [
                            @foreach($stats['distribution'] as $data)
                                '{{ $data['range'] }}',
                            @endforeach
                        ],
                        title: {
                            text: 'Score Range'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Number of Responses'
                        }
                    },
                    colors: ['{{ $code == "esa" ? "#3B82F6" : ($code == "ee" ? "#8B5CF6" : ($code == "eao" ? "#EC4899" : ($code == "er" ? "#10B981" : ($code == "esm" ? "#F59E0B" : ($code == "emo" ? "#EF4444" : "#6366F1"))))) }}'],
                };
                var distribution{{ ucfirst($code) }}Chart = new ApexCharts(document.querySelector("#distribution{{ ucfirst($code) }}Chart"), distribution{{ ucfirst($code) }}Options);
                distribution{{ ucfirst($code) }}Chart.render();
                @endif
                @endforeach
            });
        </script>
    @endpush
@endsection
