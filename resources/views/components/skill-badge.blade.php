@props(['skill'])

<div class="bg-white border-2 border-black p-4 rounded-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-[1px] hover:translate-y-[1px] transition-all">
    <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-extrabold text-black font-display">{{ $skill->name }}</span>
        <span class="text-xs font-mono text-[#ff6b00] font-bold">{{ $skill->proficiency }}%</span>
    </div>
    <div class="w-full bg-slate-100 border border-black rounded-full h-2 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-2 rounded-full transition-all duration-1000" 
             style="width: {{ $skill->proficiency }}%"></div>
    </div>
</div>
