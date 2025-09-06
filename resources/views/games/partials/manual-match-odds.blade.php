@php
    $odds = $match->odds ?? [];
@endphp

<!-- 1X2 Market -->
<div class="market-group">
    <div class="market-title">Maç Sonucu</div>
    <div class="odds-container">
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="match_result" 
             data-outcome="home"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'match_result', 'home', {{ $odds['match_result']['home'] ?? 2.00 }}, 'Ev Sahibi')">
            <span class="odd-name">1</span>
            <span class="odd-value">{{ $odds['match_result']['home'] ?? 2.00 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="match_result" 
             data-outcome="draw"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'match_result', 'draw', {{ $odds['match_result']['draw'] ?? 3.20 }}, 'Beraberlik')">
            <span class="odd-name">X</span>
            <span class="odd-value">{{ $odds['match_result']['draw'] ?? 3.20 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="match_result" 
             data-outcome="away"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'match_result', 'away', {{ $odds['match_result']['away'] ?? 3.50 }}, 'Deplasman')">
            <span class="odd-name">2</span>
            <span class="odd-value">{{ $odds['match_result']['away'] ?? 3.50 }}</span>
        </div>
    </div>
</div>

<!-- Over/Under 2.5 -->
<div class="market-group">
    <div class="market-title">Alt/Üst 2.5</div>
    <div class="odds-container">
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="over_under_2_5" 
             data-outcome="over"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'over_under_2_5', 'over', {{ $odds['over_under_2_5']['over'] ?? 1.85 }}, 'Üst 2.5')">
            <span class="odd-name">Üst</span>
            <span class="odd-value">{{ $odds['over_under_2_5']['over'] ?? 1.85 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="over_under_2_5" 
             data-outcome="under"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'over_under_2_5', 'under', {{ $odds['over_under_2_5']['under'] ?? 1.95 }}, 'Alt 2.5')">
            <span class="odd-name">Alt</span>
            <span class="odd-value">{{ $odds['over_under_2_5']['under'] ?? 1.95 }}</span>
        </div>
    </div>
</div>

<!-- Both Teams Score -->
<div class="market-group">
    <div class="market-title">Karşılıklı Gol</div>
    <div class="odds-container">
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="both_teams_score" 
             data-outcome="yes"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'both_teams_score', 'yes', {{ $odds['both_teams_score']['yes'] ?? 1.75 }}, 'KG Var')">
            <span class="odd-name">Var</span>
            <span class="odd-value">{{ $odds['both_teams_score']['yes'] ?? 1.75 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="both_teams_score" 
             data-outcome="no"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'both_teams_score', 'no', {{ $odds['both_teams_score']['no'] ?? 2.05 }}, 'KG Yok')">
            <span class="odd-name">Yok</span>
            <span class="odd-value">{{ $odds['both_teams_score']['no'] ?? 2.05 }}</span>
        </div>
    </div>
</div>

<!-- Double Chance -->
<div class="market-group">
    <div class="market-title">Çifte Şans</div>
    <div class="odds-container">
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="double_chance" 
             data-outcome="1X"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'double_chance', '1X', {{ $odds['double_chance']['1X'] ?? 1.30 }}, '1X')">
            <span class="odd-name">1X</span>
            <span class="odd-value">{{ $odds['double_chance']['1X'] ?? 1.30 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="double_chance" 
             data-outcome="12"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'double_chance', '12', {{ $odds['double_chance']['12'] ?? 1.25 }}, '12')">
            <span class="odd-name">12</span>
            <span class="odd-value">{{ $odds['double_chance']['12'] ?? 1.25 }}</span>
        </div>
        <div class="odd-box" 
             data-match-id="manual-{{ $match->id }}" 
             data-market="double_chance" 
             data-outcome="X2"
             onclick="addManualMatchToBetSlip('manual-{{ $match->id }}', 'double_chance', 'X2', {{ $odds['double_chance']['X2'] ?? 1.45 }}, 'X2')">
            <span class="odd-name">X2</span>
            <span class="odd-value">{{ $odds['double_chance']['X2'] ?? 1.45 }}</span>
        </div>
    </div>
</div>
