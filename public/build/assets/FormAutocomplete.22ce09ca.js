import{r as v,o as l,c as o,k as d,b as n,t as m,l as h,u as y,d as w,w as k,F as b,v as S,T as V}from"./app.7955817d.js";import{t as C}from"./throttle.6e5a8d03.js";const N={class:"relative"},z={class:"w-full"},B=["for"],T={class:"relative"},_=["id","name","value"],F=n("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2"},[n("svg",{xmlns:"http://www.w3.org/2000/svg",class:"h-4 w-4",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},[n("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"})])],-1),I={key:1,class:"text-red-700 mt-2 text-sm"},$=["onClick"],D={__name:"FormAutocomplete",props:{modelValue:{type:String,default:""},modelKey:{type:String,default:""},label:{type:String,default:""},endpoint:{type:String,default:""},params:{type:String,default:""},name:{type:String,required:!0},error:{type:String,default:""},lengthToStart:{type:Number,default:3}},emits:["update:modelValue","autosave"],setup(e,{emit:u}){const c=e,f=v([]),r=v(null),t=v(!1),p=C(function(){if(u("update:modelValue",r.value.value),r.value.value.length<c.lengthToStart){t.value&&(t.value=!1),r.value.value||u("autosave");return}window.axios.get(c.endpoint+"?search="+r.value.value+c.params).then(a=>{f.value=a.data.length?a.data:["No match found"],t.value=!0}).catch(a=>{console.log(a)})},300),g=a=>{r.value.value=a,t.value=!1,u("update:modelValue",a),u("autosave")};return(a,i)=>(l(),o("div",N,[t.value?(l(),o("div",{key:0,class:"fixed inset-0 z-10",onClick:i[0]||(i[0]=s=>t.value=!1)})):d("",!0),n("div",z,[e.label?(l(),o("label",{key:0,class:"form-label",for:e.name},m(e.label)+" : ",9,B)):d("",!0),n("div",T,[n("input",{type:"text",class:h(["form-input",{"border-red-400 text-red-400":e.error}]),onInput:i[1]||(i[1]=(...s)=>y(p)&&y(p)(...s)),id:e.name,name:e.name,ref_key:"input",ref:r,value:e.modelValue},null,42,_),F]),e.error?(l(),o("div",I,m(e.error),1)):d("",!0)]),w(V,{name:"fade-appear"},{default:k(()=>[t.value?(l(),o("div",{key:0,class:h(["absolute mt-1 bg-white rounded border-2 border-yellow-200 shadow-xl w-full max-h-44 py-2 overflow-y-scroll z-20 origin-top",{"scale-100 opacity-100":t.value}])},[(l(!0),o(b,null,S(f.value,(s,x)=>(l(),o("button",{class:"block w-full py-1 px-2 lg:px-3 hover:bg-primary hover:text-accent text-left",key:x,onClick:j=>g(s)},m(s),9,$))),128))],2)):d("",!0)]),_:1})]))}};export{D as _};