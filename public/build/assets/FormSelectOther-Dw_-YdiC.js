import{_ as u}from"./FormInput-FD2rBO-Y.js";import{M as f}from"./ModalDialog-i1lJFlzP.js";import{r as d,b as c,k as b,e as n,f as r,d as l,t as h,D as v}from"./app-CC1FPugz.js";const y={class:"font-semibold text-dark-theme-light"},_={class:"py-4 my-2 md:py-6 md:my-4 border-t border-b border-bitter-theme-light"},g={class:"flex justify-end items-center"},I=["disabled"],x={__name:"FormSelectOther",props:{placeholder:{type:String,default:"โปรดระบุ"}},emits:["closed"],setup(m,{expose:i}){const p=d(null),o=d(""),a=d(null);return i({open:()=>{a.value.open()}}),(s,e)=>(c(),b(v,{to:"body"},[n(f,{ref_key:"modalDialog",ref:a,"width-mode":"form-cols-1",onOpened:e[2]||(e[2]=t=>s.$refs.otherItemInput.focus()),onClosed:e[3]||(e[3]=t=>s.$emit("closed",o.value))},{header:r(()=>[l("div",y,h(m.placeholder),1)]),body:r(()=>[l("div",_,[n(u,{modelValue:o.value,"onUpdate:modelValue":e[0]||(e[0]=t=>o.value=t),name:"otherItemModel",ref_key:"otherItemInput",ref:p},null,8,["modelValue"])])]),footer:r(()=>[l("div",g,[l("button",{class:"btn btn-accent px-5",onClick:e[1]||(e[1]=t=>s.$refs.modalDialog.close()),disabled:!o.value}," Add ",8,I)])]),_:1},512)]))}};export{x as default};
