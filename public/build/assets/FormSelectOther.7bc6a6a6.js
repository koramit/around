import{r as n,B as p,o as c,s as f,d as m,w as i,b as r,t as h,H as b}from"./app.6b6092b7.js";import{_ as g}from"./FormInput.70e74dc7.js";import{M as y}from"./ModalDialog.9aee1494.js";function V(){const d=n(null),o=p({placeholder:"",configs:null,input:""});return{selectOtherInput:d,selectOther:o,selectOtherClosed:(e,s=!1)=>{if(!e){o.input.setOther("");return}s?o.configs.push({value:e,label:e}):o.configs.push(e),o.input.setOther(e)}}}const _={class:"font-semibold text-dark-theme-light"},v={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light"},I={class:"flex justify-end items-center"},O=["disabled"],B={__name:"FormSelectOther",props:{placeholder:{type:String,default:"\u0E42\u0E1B\u0E23\u0E14\u0E23\u0E30\u0E1A\u0E38"}},emits:["closed"],setup(d,{expose:o}){const u=n(null),e=n(""),s=n(null);return o({open:()=>{s.value.open()}}),(a,t)=>(c(),f(b,{to:"body"},[m(y,{ref_key:"modalDialog",ref:s,"width-mode":"form-cols-1",onOpened:t[2]||(t[2]=l=>a.$refs.otherItemInput.focus()),onClosed:t[3]||(t[3]=l=>a.$emit("closed",e.value))},{header:i(()=>[r("div",_,h(d.placeholder),1)]),body:i(()=>[r("div",v,[m(g,{modelValue:e.value,"onUpdate:modelValue":t[0]||(t[0]=l=>e.value=l),name:"otherItemModel",ref_key:"otherItemInput",ref:u},null,8,["modelValue"])])]),footer:i(()=>[r("div",I,[r("button",{class:"btn btn-accent px-5",onClick:t[1]||(t[1]=l=>a.$refs.modalDialog.close()),disabled:!e.value}," Add ",8,O)])]),_:1},512)]))}};export{B as _,V as u};
