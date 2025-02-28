<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Emotional Intelligence Assessment Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #1e40af;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .summary-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box h3 {
            color: #0369a1;
            margin-top: 0;
            font-size: 15px;
        }
        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .stat-label {
            font-weight: bold;
            color: #475569;
        }
        .category-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .category-box {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            background-color: #f8fafc;
        }
        .category-box h4 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #1e40af;
            font-size: 14px;
        }
        .meter {
            height: 10px;
            background-color: #e2e8f0;
            border-radius: 5px;
            position: relative;
            margin: 8px 0;
        }
        .meter-fill {
            height: 100%;
            border-radius: 5px;
            background-color: #3b82f6;
        }
        .level {
            font-weight: bold;
            margin-top: 5px;
            font-size: 11px;
        }
        .level.very-low { color: #dc2626; }
        .level.low { color: #ea580c; }
        .level.average { color: #ca8a04; }
        .level.high { color: #16a34a; }
        .level.very-high { color: #059669; }
        .description {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Emotional Intelligence Assessment Report</h1>
    <p>{{ $response->completed_at ? $response->completed_at->format('F j, Y') : date('F j, Y') }}</p>
    <p>Completion Code: {{ $response->completion_code }}</p>
</div>

<div class="section">
    <h2>Overall Emotional Intelligence</h2>
    <div class="summary-box">
        <h3>Your Emotional Intelligence Score</h3>

        <div class="summary-stats">
            <div>
                <div class="stat-label">Overall Score:</div>
                <div>{{ $eiInterpretation['total_ei']['score'] }}/5</div>
            </div>
            <div>
                <div class="stat-label">Level:</div>
                <div>{{ $eiInterpretation['total_ei']['level'] }}</div>
            </div>
            <div>
                <div class="stat-label">Percentile:</div>
                <div>{{ $eiInterpretation['total_ei']['percentage'] }}%</div>
            </div>
        </div>

        <div class="meter">
            <div class="meter-fill" style="width: {{ $eiInterpretation['total_ei']['percentage'] }}%"></div>
        </div>

        <p>{{ $eiInterpretation['total_ei']['description'] }}</p>
        <p>{{ $eiInterpretation['total_ei']['evaluation'] }}</p>
    </div>

    <p>Emotional intelligence (EI) is the capacity to be aware of, control, and express one's emotions, and to handle interpersonal relationships judiciously and empathetically. Your assessment results are broken down across 7 key dimensions of emotional intelligence.</p>
</div>

<div class="section">
    <h2>Emotional Intelligence Dimensions</h2>

    <div class="category-grid">
        @foreach($eiInterpretation as $key => $category)
            @if($key != 'total_ei')
                <div class="category-box">
                    <h4>{{ $category['title'] }}</h4>
                    <div class="meter">
                        <div class="meter-fill" style="width: {{ $category['percentage'] }}%"></div>
                    </div>
                    <div class="level {{ strtolower(str_replace(' ', '-', $category['level'])) }}">
                        {{ $category['level'] }} ({{ $category['score'] }}/5)
                    </div>
                    <div class="description">{{ $category['description'] }}</div>
                    <div class="description"><strong>Development:</strong> {{ $category['improvement'] }}</div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<div class="page-break"></div>

<div class="section">
    <h2>Understanding Your Results</h2>

    <p>This assessment measures how often you demonstrate emotionally intelligent behaviors across seven dimensions:</p>

    <ol>
        <li><strong>Emotional Self-Awareness:</strong> Perceiving and understanding your own emotions</li>
        <li><strong>Emotional Expression:</strong> Effectively expressing your emotions</li>
        <li><strong>Emotional Awareness of Others:</strong> Perceiving and understanding others' emotions</li>
        <li><strong>Emotional Reasoning:</strong> Using emotional information in decision-making</li>
        <li><strong>Emotional Self-Management:</strong> Managing your own emotions effectively</li>
        <li><strong>Emotional Management of Others:</strong> Positively influencing the emotions of others</li>
        <li><strong>Emotional Self-Control:</strong> Controlling strong emotions appropriately</li>
    </ol>

    <p>Your scores indicate your strengths and development opportunities in each dimension. Focus on leveraging your strengths while working to improve areas with lower scores.</p>

    <h3>Score Interpretation Scale:</h3>
    <ul>
        <li><strong>Very Low (1.0-2.0):</strong> This area needs significant improvement</li>
        <li><strong>Low (2.1-3.0):</strong> This is an area where you could focus development efforts</li>
        <li><strong>Average (3.1-3.5):</strong> You demonstrate this competency at an average level</li>
        <li><strong>High (3.6-4.5):</strong> This is a strength area for you</li>
        <li><strong>Very High (4.6-5.0):</strong> This is a significant strength that you can leverage</li>
    </ul>
</div>

<div class="section">
    <h2>Recommendations for Improvement</h2>

    <p>Based on your assessment results, focus on developing these key areas:</p>

    <ol>
        @php
            $lowestScoreCategories = collect($eiInterpretation)
                ->filter(fn($cat, $key) => $key !== 'total_ei')
                ->sortBy('score')
                ->take(3);
        @endphp

        @foreach($lowestScoreCategories as $category)
            <li>
                <strong>{{ $category['title'] }}:</strong> {{ $category['improvement'] }}
            </li>
        @endforeach
    </ol>

    <p>Remember that emotional intelligence can be developed with practice and consistent effort. Regular self-reflection, seeking feedback, and consciously applying these skills in your daily work will help you improve over time.</p>
</div>

<div class="footer">
    <p>This report is based on the Genos Emotional Intelligence Self-Assessment</p>
    <p>© {{ date('Y') }} CPA Survey System. All rights reserved.</p>
</div>
</body>
</html>
