.full-height {
    height: 100vh;
}

.flex-center {
    align-items: center;
    display: flex;
    justify-content: center;
}

.position-ref {
    position: relative;
}

.content {
    text-align: center;
    width: 100%;
}

.title {
    font-size: 84px;
}

.links-item > a {
    color: #636b6f;
    padding: 0 25px;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: .1rem;
    text-decoration: none;
    text-transform: uppercase;
}

.m-b-md {
    margin-bottom: 30px;
}

.machine-group {
    position: relative;
    display: block;
    width: calc(100% - 60px);
    margin: auto;
    font-size: 0px;
    text-align: center;
}

.machine-item {
    position: relative;
    display: inline-block;
    vertical-align: top;
    width: calc((100% / 3) - 30px);
    padding: 30px 20px;
    /*border: 1px solid #feeb2d;*/
    background: #feeb2d;
    box-shadow: 10px 10px 0px #4c4c4c;
    margin-right: 30px;
    font-size: 16px;
    text-align: left;
    line-height: 20px;
    height: 200px;
    overflow: auto;
    color: #222;
    margin-bottom: 30px;
}

.machine-item:nth-child(2) {
    margin-right: 0px;
}

.machine-item:last-child {
    margin-right: 0px;
    display: block;
    margin: auto;
    width: calc(100% - 30px - (100% / 3));
    margin-bottom: 50px;
    height: 530px;
}

.machine-title {
    position: relative;
    display: block;
    font-size: 18px;
    font-weight: normal;
    font-weight: bold;
    margin: 0px 0px 20px;
}

.machine-list-item {
    position: relative;
    display: block;
    padding: 10px;
    border-bottom: 1px solid #222;
}

.machine-list-item span {
    position: relative;
    display: block;
}

.machine-list-item span:first-child {
    font-size: 12px;
}

.machine-list-item:last-child {
    border-bottom: 0px;
}