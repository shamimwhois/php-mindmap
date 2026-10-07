<?php
// PHP Mind Map Generator - generates the full HTML file
$topics = json_decode(file_get_contents(__DIR__ . '/topics.json'), true);

$html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PHP Fundamentals Mind Map</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0a0f;color:#e2e8f0;font-family:"Segoe UI",system-ui,-apple-system,sans-serif;min-height:100vh}
.grid-bg{background-image:linear-gradient(rgba(99,102,241,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.07) 1px,transparent 1px);background-size:40px 40px;position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:0}
.app{position:relative;z-index:1}
.card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:12px;transition:all .3s ease}
.card:hover{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.12)}
.card-hdr{cursor:pointer;padding:20px;display:flex;align-items:center;gap:16px;user-select:none}
.card-body{padding:0 20px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px}
.sub-btn{padding:10px 14px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid rgba(255,255,255,.1);transition:all .25s ease;text-align:left;display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.03)}
.sub-btn:hover{transform:translateY(-1px);box-shadow:0 4px 15px rgba(0,0,0,.3)}
.sub-btn.done{opacity:.5}
.dot{width:18px;height:18px;border-radius:50%;border:2px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;transition:all .25s}
.sub-btn.done .dot{background:rgba(34,197,94,.3);border-color:#22c55e;color:#22c55e}
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.75);backdrop-filter:blur(8px);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px}
.modal-box{background:#12121a;border:1px solid rgba(255,255,255,.1);border-radius:16px;width:100%;max-width:900px;max-height:90vh;overflow-y:auto;padding:32px;position:relative;animation:fadeIn .2s ease}
.modal-box::-webkit-scrollbar{width:6px}
.modal-box::-webkit-scrollbar-track{background:transparent}
.modal-box::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:3px}
@keyframes fadeIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
.sec{margin-bottom:24px}
.sec-lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:10px;display:flex;align-items:center;gap:8px}
.sec-ct{font-size:14px;line-height:1.7;color:#cbd5e1}
pre{background:#0d0d14;border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:16px;overflow-x:auto;font-size:13px;line-height:1.6;margin:8px 0;font-family:"Fira Code","Cascadia Code","Consolas",monospace}
.kw{color:#f87171;font-weight:600}
.fn{color:#c084fc}
.str{color:#60a5fa}
.var{color:#fb923c}
.cmt{color:#64748b;font-style:italic}
.num{color:#67e8f9}
.op{color:#fbbf24}
.punct{color:#94a3b8}
.use-list{list-style:none;padding:0}
.use-list li{padding:6px 0 6px 20px;position:relative;font-size:13px;line-height:1.6}
.use-list li::before{content:"\203A";position:absolute;left:0;font-weight:700;color:#818cf8}
.mis{padding:6px 0 6px 20px;position:relative;font-size:13px;line-height:1.6}
.mis::before{content:"\2715";position:absolute;left:0;color:#f87171;font-weight:700}
.tip{padding:6px 0 6px 20px;position:relative;font-size:13px;line-height:1.6}
.tip::before{content:"\2726";position:absolute;left:0;color:#34d399;font-weight:700}
.intv{padding:8px 0 8px 20px;position:relative;font-size:13px;line-height:1.6}
.intv::before{content:"Q";position:absolute;left:0;color:#fbbf24;font-weight:700;font-size:11px;background:rgba(251,191,36,.15);width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;top:8px}
.close-btn{position:sticky;top:0;float:right;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);color:#94a3b8;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;z-index:5}
.close-btn:hover{background:rgba(255,255,255,.15);color:#e2e8f0}
.bar{display:flex;gap:10px;flex-wrap:wrap}
.bar button{padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:#e2e8f0;cursor:pointer;transition:all .2s}
.bar button:hover{background:rgba(255,255,255,.1)}
.pbar{height:4px;background:rgba(255,255,255,.08);border-radius:2px;overflow:hidden;margin-top:8px}
.pfill{height:100%;border-radius:2px;transition:width .4s ease}
@media(max-width:640px){.card-body{grid-template-columns:1fr}.modal-box{padding:20px;margin:10px}}
</style>
</head>
<body>
<div class="grid-bg"></div>
<div class="app" x-data="mindmap()" x-init="init()">
<header class="max-w-7xl mx-auto px-4 pt-8 pb-6">
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
<div>
<h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">PHP Fundamentals Mind Map</h1>
<p class="text-gray-500 mt-2 text-sm">21 Topics \xe2\x80\xa2 All Subtopics \xe2\x80\xa2 Interactive Learning</p>
</div>
<div class="flex flex-col items-end gap-2">
<p class="text-xs text-gray-500">Progress: <span x-text="completedCount"></span> / <span x-text="totalCount"></span> (<span x-text="progressPercent"></span>%)</p>
<div class="pbar w-48"><div class="pfill bg-gradient-to-r from-indigo-500 to-purple-500" :style="\'width:\'+progressPercent+\'%\'"></div></div>
</div>
</div>
<div class="bar mt-4">
<button @click="expandAll()" class="text-xs">Expand All</button>
<button @click="collapseAll()" class="text-xs">Collapse All</button>
<button @click="resetProgress()" class="text-xs">Reset Progress</button>
</div>
</header>
<main class="max-w-7xl mx-auto px-4 pb-16">
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
<template x-for="(topic,i) in topics" :key="i">
<div class="card" x-data="{open:false}">
<div class="card-hdr" @click="open=!open">
<span class="text-2xl" x-text="topic.icon"></span>
<div class="flex-1 min-w-0">
<h3 class="font-bold text-base" x-text="topic.name"></h3>
<p class="text-xs text-gray-500 mt-0.5" x-text="topic.subs.length+\' subtopics\'"></p>
<div class="pbar mt-2" style="height:3px"><div class="pfill" :style="\'width:\'+getTopicProgress(i)+\'%;background:\'+topic.color"></div></div>
</div>
<svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="open?\'rotate-180\' :\'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
</div>
<div class="card-body" x-show="open" x-collapse x-cloak>
<template x-for="(sub,si) in topic.subs" :key="si">
<button class="sub-btn" :class="isCompleted(topic.id,si)?\'done\' :\'\'" @click.stop="openModal(topic,si)">
<span class="dot" :style="isCompleted(topic.id,si)?\'background:\'+topic.color+\'33;border-color:\'+topic.color :\'\'">\xe2\x9c\x93</span>
<span x-text="sub.name" class="flex-1"></span>
<span class="text-lg" x-text="topic.icon"></span>
</button>
</template>
</div>
</div>
</template>
</div>
</main>
<div class="modal-bg" x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.self="showModal=false" @keydown.escape.window="showModal=false" x-cloak>
<div class="modal-box" @click.stop>
<button class="close-btn" @click="showModal=false">&times;</button>
<template x-if="modalData">
<div>
<div class="flex items-center gap-3 mb-2">
<span class="text-3xl" x-text="modalData.topic.icon"></span>
<div>
<h2 class="text-xl font-bold" x-text="modalData.sub.name" :style="\'color:\'+modalData.topic.color"></h2>
<p class="text-xs text-gray-500" x-text="modalData.topic.name+\' \xe2\x86\x92 \'+modalData.sub.name"></p>
</div>
</div>
<div class="sec">
<div class="sec-lbl" :style="\'color:\'+modalData.topic.color">\xf0\x9f\x93\x96 Explanation</div>
<div class="sec-ct" x-text="modalData.sub.explanation"></div>
</div>
<div class="sec">
<div class="sec-lbl" :style="\'color:\'+modalData.topic.color">\xf0\x9f\x94\xa7 Syntax</div>
<pre x-html="modalData.sub.syntax"></pre>
</div>
<div class="sec">
<div class="sec-lbl" :style="\'color:\'+modalData.topic.color">\xf0\x9f\x92\xbb Code Example</div>
<pre x-html="modalData.sub.codeExample"></pre>
</div>
<div class="sec">
<div class="sec-lbl" :style="\'color:\'+modalData.topic.color">\xf0\x9f\x8e\xaf Use Cases</div>
<ul class="use-list"><template x-for="u in modalData.sub.useCases"><li x-text="u"></li></template></ul>
</div>
<div class="sec">
<div class="sec-lbl text-red-400">\xe2\x9a\xa0\xef\xb8\x8f Common Mistakes</div>
<template x-for="m in modalData.sub.mistakes"><div class="mis" x-text="m"></div></template>
</div>
<div class="sec">
<div class="sec-lbl text-emerald-400">\xf0\x9f\x92\xa1 Pro Tips</div>
<template x-for="t in modalData.sub.tips"><div class="tip" x-text="t"></div></template>
</div>
<div class="sec">
<div class="sec-lbl text-amber-400">\xf0\x9f\x8e\xa4 Interview Questions</div>
<template x-for="q in modalData.sub.interview"><div class="intv" x-text="q"></div></template>
</div>
<div class="flex gap-3 mt-6">
<button class="flex-1 py-2 px-4 rounded-lg text-sm font-semibold transition-all" :style="\'background:\'+modalData.topic.color+\'22;color:\'+modalData.topic.color+\';border:1px solid \'+modalData.topic.color+\'44\'" @click="toggleComplete(modalData.topicId,modalData.subIndex)">
<span x-text="isCompleted(modalData.topicId,modalData.subIndex)?\'Mark Incomplete\':\'Mark Complete\'"></span>
</button>
</div>
</div>
</template>
</div>
</div>
</div>
<script>
function mindmap(){return{
showModal:false,modalData:null,completed:{},
topics:' . json_encode($topics, JSON_HEX_TAG | JSON_HEX_AMP) . ',
totalCount:0,
get completedCount(){return Object.keys(this.completed).length},
get progressPercent(){return this.totalCount?Math.round(this.completedCount/this.totalCount*100):0},
getTopicProgress(i){const t=this.topics[i];let d=0;for(let s=0;s<t.subs.length;s++){if(this.completed[t.id+\'_\'+s])d++}return t.subs.length?Math.round(d/t.subs.length*100):0},
isCompleted(tid,si){return this.completed[tid+\'_\'+si]||false},
toggleComplete(tid,si){const k=tid+\'_\'+si;if(this.completed[k]){delete this.completed[k]}else{this.completed[k]=true}this.saveProgress()},
saveProgress(){localStorage.setItem(\'php_mm_progress\',JSON.stringify(this.completed))},
loadProgress(){const d=localStorage.getItem(\'php_mm_progress\');if(d)this.completed=JSON.parse(d)},
expandAll(){document.querySelectorAll(\'[x-data]\').forEach(el=>{const a=Alpine.\$data(el);if(a&&\'open\'in a)a.open=true})},
collapseAll(){document.querySelectorAll(\'[x-data]\').forEach(el=>{const a=Alpine.\$data(el);if(a&&\'open\'in a)a.open=false})},
resetProgress(){if(confirm(\'Reset all progress?\')){this.completed={};this.saveProgress()}},
openModal(topic,si){this.modalData={topic,sub:topic.subs[si],topicId:topic.id,subIndex:si};this.showModal=true},
init(){this.loadProgress();this.totalCount=this.topics.reduce((s,t)=>s+t.subs.length,0)}
}}';
file_put_contents(__DIR__ . '/php-mindmap-full.html', $html);
echo "HTML file generated: " . __DIR__ . '/php-mindmap-full.html' . PHP_EOL;
echo "File size: " . number_format(filesize(__DIR__ . '/php-mindmap-full.html')) . " bytes" . PHP_EOL;
