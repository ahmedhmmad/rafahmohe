<!-- CSS Styles -->

<style>
    @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300&display=swap');

    body {
        font-family: "Almarai", sans-serif;
        line-height: 1.3em;
        min-width: 920px;
    }

    .history-tl-container {
        width: 50%;
        margin: auto;
        position: relative;
    }

    .history-tl-container ul.tl {
        margin: 20px 0;
        padding: 0;
    }

    .history-tl-container ul.tl li {
        list-style: none;
        border-left: 1px dashed #86D6FF;
        padding: 10px 0 10px 40px;
        position: relative;
    }

    .history-tl-container ul.tl li::before {
        content: "";
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #86D6FF;
    }

    .history-tl-container ul.tl li .item-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .history-tl-container ul.tl li .item-detail {
        color: rgba(0, 0, 0, 0.5);
        font-size: 14px;
    }

    .history-tl-container ul.tl li .timestamp {
        color: #8D8D8D;
        font-size: 14px;
        position: absolute;
        left: -60px; /* Move the timestamp to the left */
        top: 50%;
        transform: translateY(-50%);
    }

    .history-tl-container ul.tl li:first-child::before {
        top: 50%;
    }

    .history-tl-container ul.tl li:last-child::before {
        bottom: 50%;
    }

    .history-tl-container ul.tl li .item-icon {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #258CC7;
        color: #fff;
        text-align: center;
        line-height: 20px;
    }

    .history-tl-container ul.tl li .item-content {
        margin-left: 35px;
    }

</style>

<!-- HTML Content -->
<div class="history-tl-container">
    <h5><strong>التاريخ:</strong> {{$timeline[0]['visit_date']}}</h5>

    <ul class="tl">
        @foreach ($timeline as $item)
            <li class="tl-item">
                <div class="item-icon"></div>
                <div class="item-content">
                    <div class="timestamp">{{ substr($item['coming_time'], 0, 5) }}</div>
                    <div class="item-title">{{ $item['school']->name }}</div>
{{--                    <div class="item-detail">{{ $item['purpose'] }}</div>--}}
                </div>
            </li>
        @endforeach
    </ul>
</div>
