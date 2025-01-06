import{U as L,r as k,o as V,h as w,c as x,t as F,g as H,b as W,p as R}from"./app-CsDEoyKE.js";var y=new Map;function Y(t){var r=y.get(t);r&&r.destroy()}function C(t){var r=y.get(t);r&&r.update()}var v=null;typeof window>"u"?((v=function(t){return t}).destroy=function(t){return t},v.update=function(t){return t}):((v=function(t,r){return t&&Array.prototype.forEach.call(t.length?t:[t],function(p){return function(e){if(e&&e.nodeName&&e.nodeName==="TEXTAREA"&&!y.has(e)){var i,a=null,o=window.getComputedStyle(e),h=(i=e.value,function(){s({testForHeightReduction:i===""||!e.value.startsWith(i),restoreTextAlign:null}),i=e.value}),c=(function(f){e.removeEventListener("autosize:destroy",c),e.removeEventListener("autosize:update",l),e.removeEventListener("input",h),window.removeEventListener("resize",l),Object.keys(f).forEach(function(d){return e.style[d]=f[d]}),y.delete(e)}).bind(e,{height:e.style.height,resize:e.style.resize,textAlign:e.style.textAlign,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",c),e.addEventListener("autosize:update",l),e.addEventListener("input",h),window.addEventListener("resize",l),e.style.overflowX="hidden",e.style.wordWrap="break-word",y.set(e,{destroy:c,update:l}),l()}function s(f){var d,u,b=f.restoreTextAlign,g=b===void 0?null:b,E=f.testForHeightReduction,N=E===void 0||E,S=o.overflowY;if(e.scrollHeight!==0&&(o.resize==="vertical"?e.style.resize="none":o.resize==="both"&&(e.style.resize="horizontal"),N&&(d=function(n){for(var A=[];n&&n.parentNode&&n.parentNode instanceof Element;)n.parentNode.scrollTop&&A.push([n.parentNode,n.parentNode.scrollTop]),n=n.parentNode;return function(){return A.forEach(function(T){var m=T[0],B=T[1];m.style.scrollBehavior="auto",m.scrollTop=B,m.style.scrollBehavior=null})}}(e),e.style.height=""),u=o.boxSizing==="content-box"?e.scrollHeight-(parseFloat(o.paddingTop)+parseFloat(o.paddingBottom)):e.scrollHeight+parseFloat(o.borderTopWidth)+parseFloat(o.borderBottomWidth),o.maxHeight!=="none"&&u>parseFloat(o.maxHeight)?(o.overflowY==="hidden"&&(e.style.overflow="scroll"),u=parseFloat(o.maxHeight)):o.overflowY!=="hidden"&&(e.style.overflow="hidden"),e.style.height=u+"px",g&&(e.style.textAlign=g),d&&d(),a!==u&&(e.dispatchEvent(new Event("autosize:resized",{bubbles:!0})),a=u),S!==o.overflow&&!g)){var z=o.textAlign;o.overflow==="hidden"&&(e.style.textAlign=z==="start"?"end":"start"),s({restoreTextAlign:z,testForHeightReduction:!0})}}function l(){s({testForHeightReduction:!0,restoreTextAlign:null})}}(p)}),t}).destroy=function(t){return t&&Array.prototype.forEach.call(t.length?t:[t],Y),t},v.update=function(t){return t&&Array.prototype.forEach.call(t.length?t:[t],C),t});var X=v;const M={class:"w-full"},$=["for"],j=["id","name","type","placeholder","pattern","readonly","value"],q={key:1,class:"text-red-700 mt-2 text-sm"},I={__name:"FormTextarea",props:{modelValue:{type:String,default:""},name:{type:String,required:!0},label:{type:String,default:""},type:{type:String,default:"text"},placeholder:{type:String,default:""},pattern:{type:String,default:""},readonly:{type:Boolean},error:{type:String,default:""}},emits:["autosave","update:modelValue"],setup(t,{expose:r,emit:p}){const e=p,i=L(()=>e("autosave"),2500),a=k(null);V(()=>{X(a.value)});const o=()=>{e("update:modelValue",a.value.value),i()};return r({focus:()=>a.value.focus()}),(c,s)=>(w(),x("div",M,[t.label?(w(),x("label",{key:0,class:"form-label",for:t.name},F(t.label)+" :",9,$)):H("",!0),W("textarea",{id:t.name,name:t.name,ref_key:"textarea",ref:a,onInput:o,onChange:s[0]||(s[0]=l=>c.$emit("autosave")),type:t.type,placeholder:t.placeholder,pattern:t.pattern,readonly:t.readonly,value:t.modelValue,class:R(["form-input form-scroll-mt",{"border-red-400 text-red-400":t.error}])},null,42,j),t.error?(w(),x("div",q,F(t.error),1)):H("",!0)]))}};export{I as _};
