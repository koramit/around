import{A as L,r as F,f as B,o as b,c as x,t as S,i as N,a as C,k as T}from"./app.a2148d35.js";var d,m,p=typeof Map=="function"?new Map:(d=[],m=[],{has:function(t){return d.indexOf(t)>-1},get:function(t){return m[d.indexOf(t)]},set:function(t,o){d.indexOf(t)===-1&&(d.push(t),m.push(o))},delete:function(t){var o=d.indexOf(t);o>-1&&(d.splice(o,1),m.splice(o,1))}}),k=function(t){return new Event(t,{bubbles:!0})};try{new Event("test")}catch{k=function(o){var u=document.createEvent("Event");return u.initEvent(o,!0,!1),u}}function M(t){var o=p.get(t);o&&o.destroy()}function W(t){var o=p.get(t);o&&o.update()}var f=null;typeof window=="undefined"||typeof window.getComputedStyle!="function"?((f=function(t){return t}).destroy=function(t){return t},f.update=function(t){return t}):((f=function(t,o){return t&&Array.prototype.forEach.call(t.length?t:[t],function(u){return function(e){if(e&&e.nodeName&&e.nodeName==="TEXTAREA"&&!p.has(e)){var r,s=null,g=null,h=null,c=function(){e.clientWidth!==g&&a()},y=function(l){window.removeEventListener("resize",c,!1),e.removeEventListener("input",a,!1),e.removeEventListener("keyup",a,!1),e.removeEventListener("autosize:destroy",y,!1),e.removeEventListener("autosize:update",a,!1),Object.keys(l).forEach(function(n){e.style[n]=l[n]}),p.delete(e)}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",y,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",a,!1),window.addEventListener("resize",c,!1),e.addEventListener("input",a,!1),e.addEventListener("autosize:update",a,!1),e.style.overflowX="hidden",e.style.wordWrap="break-word",p.set(e,{destroy:y,update:a}),(r=window.getComputedStyle(e,null)).resize==="vertical"?e.style.resize="none":r.resize==="both"&&(e.style.resize="horizontal"),s=r.boxSizing==="content-box"?-(parseFloat(r.paddingTop)+parseFloat(r.paddingBottom)):parseFloat(r.borderTopWidth)+parseFloat(r.borderBottomWidth),isNaN(s)&&(s=0),a()}function z(l){var n=e.style.width;e.style.width="0px",e.style.width=n,e.style.overflowY=l}function w(){if(e.scrollHeight!==0){var l=function(n){for(var i=[];n&&n.parentNode&&n.parentNode instanceof Element;)n.parentNode.scrollTop&&(n.parentNode.style.scrollBehavior="auto",i.push([n.parentNode,n.parentNode.scrollTop])),n=n.parentNode;return function(){return i.forEach(function(v){var E=v[0];E.scrollTop=v[1],E.style.scrollBehavior=null})}}(e);e.style.height="",e.style.height=e.scrollHeight+s+"px",g=e.clientWidth,l()}}function a(){w();var l=Math.round(parseFloat(e.style.height)),n=window.getComputedStyle(e,null),i=n.boxSizing==="content-box"?Math.round(parseFloat(n.height)):e.offsetHeight;if(i<l?n.overflowY==="hidden"&&(z("scroll"),w(),i=n.boxSizing==="content-box"?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight):n.overflowY!=="hidden"&&(z("hidden"),w(),i=n.boxSizing==="content-box"?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight),h!==i){h=i;var v=k("autosize:resized");try{e.dispatchEvent(v)}catch{}}}}(u)}),t}).destroy=function(t){return t&&Array.prototype.forEach.call(t.length?t:[t],M),t},f.update=function(t){return t&&Array.prototype.forEach.call(t.length?t:[t],W),t});var A=f;const V={class:"w-full"},H=["for"],O=["id","name","type","placeholder","pattern","readonly","value"],Y={key:1,class:"text-red-700 mt-2 text-sm"},$={__name:"FormTextarea",props:{modelValue:{type:String,default:""},name:{type:String,required:!0},label:{type:String,default:""},type:{type:String,default:"text"},placeholder:{type:String,default:""},pattern:{type:String,default:""},readonly:{type:Boolean},error:{type:String,default:""}},emits:["autosave","update:modelValue"],setup(t,{expose:o,emit:u}){const e=L(()=>u("autosave"),2500),r=F(null);B(()=>{A(r.value)});const s=()=>{u("update:modelValue",r.value.value),e()};return o({focus:()=>r.value.focus()}),(h,c)=>(b(),x("div",V,[t.label?(b(),x("label",{key:0,class:"form-label",for:t.name},S(t.label)+" :",9,H)):N("",!0),C("textarea",{id:t.name,name:t.name,ref_key:"textarea",ref:r,onInput:s,onChange:c[0]||(c[0]=y=>h.$emit("autosave")),type:t.type,placeholder:t.placeholder,pattern:t.pattern,readonly:t.readonly,value:t.modelValue,class:T(["form-input form-scroll-mt",{"border-red-400 text-red-400":t.error}])},null,42,O),t.error?(b(),x("div",Y,S(t.error),1)):N("",!0)]))}};export{$ as _};
