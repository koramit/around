import{_ as c}from"./FormInput-qCU0J5nu.js";import{r as u,c as s,d as p,b as r,F as o,l as d,h as t,f as i,t as m}from"./app-Cw0y1MbD.js";const _={class:"mt-6 space-y-2"},f=["innerHTML"],h=["href"],g={__name:"JournalPage",props:{files:{type:Array,required:!0}},setup(n){const a=u("");return(x,l)=>(t(),s(o,null,[p(c,{label:"search",name:"search",modelValue:a.value,"onUpdate:modelValue":l[0]||(l[0]=e=>a.value=e)},null,8,["modelValue"]),r("ul",_,[(t(!0),s(o,null,d([...n.files].filter(e=>e.toLowerCase().indexOf(a.value.toLocaleLowerCase())!==-1).sort(),e=>(t(),s("li",{key:e,class:"py-1 px-2 bg-white rounded"},[r("span",{innerHTML:e.replace("f/j/","").replace(new RegExp(a.value,"i"),`<span class='bg-complement text-white'>${a.value}</span>`)},null,8,f),i(" "+m()+" ",1),r("a",{class:"ml-4 text-blue-500",href:`/journal/show?key=${e}`}," download ",8,h)]))),128))])],64))}};export{g as default};
