import{r as m,b as l,c as o,h as d,d as n,t as v,s as p,u as y,e as w,f as k,F as S,m as V,q as C}from"./app-CC1FPugz.js";import{t as B}from"./throttle-Dvu3cFSN.js";const N={class:"relative"},z={class:"w-full"},F=["for"],T={class:"relative"},q=["id","name","value","disabled"],I=n("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2"},[n("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-4 w-4",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[n("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"})])],-1),$={key:1,class:"text-red-700 mt-2 text-sm"},j=["onClick"],K={__name:"FormAutocomplete",props:{modelValue:{type:String,default:""},modelKey:{type:String,default:""},label:{type:String,default:""},endpoint:{type:String,default:""},params:{type:String,default:""},name:{type:String,required:!0},error:{type:String,default:""},lengthToStart:{type:Number,default:3},disabled:{type:Boolean}},emits:["update:modelValue","autosave"],setup(e,{emit:g}){const u=g,c=e,f=m([]),s=m(null),t=m(!1),h=B(function(){if(u("update:modelValue",s.value.value),s.value.value.length<c.lengthToStart){t.value&&(t.value=!1),s.value.value||u("autosave");return}window.axios.get(c.endpoint+"?search="+s.value.value+c.params).then(a=>{f.value=a.data.length?a.data:["No match found"],t.value=!0}).catch(a=>{console.log(a)})},300),b=a=>{s.value.value=a,t.value=!1,u("update:modelValue",a),u("autosave")};return(a,i)=>(l(),o("div",N,[t.value?(l(),o("div",{key:0,class:"fixed inset-0 z-10",onClick:i[0]||(i[0]=r=>t.value=!1)})):d("",!0),n("div",z,[e.label?(l(),o("label",{key:0,class:"form-label",for:e.name},v(e.label)+" : ",9,F)):d("",!0),n("div",T,[n("input",{type:"text",class:p(["form-input form-scroll-mt",{"!border-red-400 !text-red-400":e.error}]),onInput:i[1]||(i[1]=(...r)=>y(h)&&y(h)(...r)),id:e.name,name:e.name,ref_key:"input",ref:s,value:e.modelValue,disabled:e.disabled},null,42,q),I]),e.error?(l(),o("div",$,v(e.error),1)):d("",!0)]),w(C,{name:"fade-appear"},{default:k(()=>[t.value?(l(),o("div",{key:0,class:p(["absolute mt-1 bg-white rounded border-2 border-yellow-200 shadow-xl w-full max-h-44 py-2 overflow-y-scroll z-20 origin-top",{"scale-100 opacity-100":t.value}])},[(l(!0),o(S,null,V(f.value,(r,x)=>(l(),o("button",{class:"block w-full py-1 px-2 lg:px-3 hover:bg-primary hover:text-accent text-left",key:x,onClick:A=>b(r)},v(r),9,j))),128))],2)):d("",!0)]),_:1})]))}};export{K as _};
